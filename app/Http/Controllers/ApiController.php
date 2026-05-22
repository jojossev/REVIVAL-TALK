<?php

namespace App\Http\Controllers;

use App\Models\AdSpaces;
use App\Models\Author;
use App\Models\Bookmark;
use App\Models\BreakingNews;
use App\Models\BreakingNewsView;
use App\Models\Category;
use App\Models\CommentNotification;
use App\Models\Comments;
use App\Models\CommentsFlag;
use App\Models\CommentsLike;
use App\Models\FeaturedSectionRssFeed;
use App\Models\FeaturedSections;
use App\Models\Language;
use App\Models\LiveStreaming;
use App\Models\Location;
use App\Models\News;
use App\Models\Enews;
use App\Models\News_image;
use App\Models\News_like;
use App\Models\News_view;
use App\Models\Pages;
use App\Models\SendNotification;
use App\Models\Settings;
use App\Models\SocialMedia;
use App\Models\SubCategory;
use App\Models\SurveyOption;
use App\Models\SurveyQuestion;
use App\Models\SurveyResult;
use App\Models\Tag;
use App\Models\Token;
use App\Models\RSS;
use App\Models\FeedItem;
use App\Models\User;
use App\Models\UserCategory;
use App\Models\WebSeoPages;
use App\Models\WebSetting;
use App\Jobs\FetchRssFeedJob;
use App\Services\ResponseService;
use App\Services\XMLService;
use App\Traits\ResolvesLanguage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Http;
// use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\GetFeedItemsApiRequest;


class ApiController extends Controller
{
    // trait for resolving language id or code, including validation for language id or code
    use ResolvesLanguage;

    private $toDate;
    private $toDateTime;
    private $nearest_location_measure;
    // private $lang;

    public function __construct()
    {
        $nearest_location_measure = Settings::where('type', 'nearest_location_measure')->first();
        $this->nearest_location_measure = $nearest_location_measure->message ?? 1000;
        $this->toDate = date('Y-m-d');
        $this->toDateTime = date('Y-m-d H:i:s');
    }

    public function getRssFeedById(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => ['required', 'numeric'],
            ]);
            if ($validator->fails()) {
                return ResponseService::validationError($validator->errors()->first());
            }

            $res = RSS::where('id', $request->id)->where('status', 1)->first();
            if ($res) {
                $url = $res->feed_url;
                $response = Http::get($url);
                if ($response->successful()) {
                    $xmlContent = $response->body();
                    $xmlObject = simplexml_load_string($xmlContent, "SimpleXMLElement", LIBXML_NOCDATA); // Load the XML string
                    $jsonString = json_encode($xmlObject); // Convert XML to JSON
                    $data = json_decode($jsonString, true); // Optionally, convert JSON to an associative array
                    $response = [
                        'error' => false,
                        'data' => $data,
                    ];
                } else {
                    $response = [
                        'error' => true,
                        'message' => 'Failed to fetch the XML data',
                    ];
                }
            } else {
                $response = [
                    'error' => true,
                    'message' => 'No Data Found',
                ];
            }
        } catch (Exception $e) {
            $response = [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
        return response()->json($response);
    }

    // getting all rss sources data
    public function getRssFeed(Request $request)
    {
        try {
            $request['get_user_news'] = $request->get_user_news ?? 0;

            $language_id = $this->resolveLanguageId($request);

            $offset = $request->offset ?? 0;
            $limit = $request->limit ?? 10;

            $rss = RSS::with('category:id,category_name,slug', 'sub_category:id,subcategory_name')->where('language_id', $language_id)->where('status', 1);
            if ($request->category_id) {
                $rss->where('category_id', $request->category_id);
            }
            if ($request->category_slug) {
                $category_id = Category::select('id')->where('slug', $request->category_slug)->pluck('id')->first();
                $rss->where('category_id', $category_id);
            }
            if ($request->subcategory_id) {
                $rss->where('subcategory_id', $request->subcategory_id);
            }
            if ($request->subcategory_slug) {
                $subcategory_id = SubCategory::select('id')->where('slug', $request->subcategory_slug)->pluck('id')->first();
                $rss->where('subcategory_id', $subcategory_id);
            }
            if ($request->tag_id) {
                $tag_ids = $request->tag_id; // Assuming it's a string like "4,2"
                // $rss->whereIn('tag_id', explode(',', $tag_ids));
                $rss->whereRaw('FIND_IN_SET(?, tag_id)', [$tag_ids]);
            }
            if ($request->tag_slug) {
                $tag_ids = Tag::select('id')->where('slug', $request->tag_slug)->pluck('id')->first();
                // $rss->whereIn('tag_id', explode(',', $tag_ids));
                $rss->whereRaw('FIND_IN_SET(?, tag_id)', [$tag_ids]);
            }
            if ($request->search) {
                $search = $request->search;
                $rss->where(function ($q) use ($search) {
                    $q->where('tbl_rss.feed_name', 'LIKE', "%{$search}%");
                });
            }
            $rss->select('tbl_rss.*')->orderBy('tbl_rss.id', 'DESC');

            $total = $rss->clone()->count();

            if ($total) {
                $res = $rss->clone()->skip($offset)->take($limit)->get();
                $res->each(function ($item) {
                    $item->tag = [];
                    if (isset($item->tag_id) && $item->tag_id != '') {
                        $tagNames = Tag::whereIn('id', explode(',', $item->tag_id))->distinct()->pluck('tag_name')->implode(',');
                        $item->tag_name = $tagNames;
                        $item->tag = Tag::select('id', 'tag_name', 'slug')->whereIn('id', explode(',', $item->tag_id))->get();
                    }
                });

                return ResponseService::successResponse(__('Data fetched successfully'), [
                    'total' => $total,
                    'data' => $res,
                ]);
            } else {
                return ResponseService::errorResponse(__('No Data Found'), null, config('constants.RESPONSE_CODE.NOT_FOUND'), null);
            }
        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, 'API Controller -> getRssFeed');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function checkSlugAvailability(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'slug' => 'required|string|max:255',
            ]);
            if ($validator->fails()) {
                return ResponseService::validationError($validator->errors()->first());
            }
            // checking slug is in english or not
            $slug_en = generateUniqueSlug($request->slug);

            $checkSlug = News::where('slug', $slug_en)->where('id', '!=', $request->news_id)->first();

            if (!empty($checkSlug)) {

                return ResponseService::errorResponse(__('The slug is already in use. Please choose another.'), $slug_en, config('constants.RESPONSE_CODE.VALIDATION_ERROR'), null);
            } else {

                return ResponseService::successResponse(__('This slug can be used.'), [
                    'slug' => $slug_en,
                ]);
            }
        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, 'API Controller -> checkSlugAvailability');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function deleteNewsImages(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);
            if ($validator->fails()) {
                return ResponseService::validationError($validator->errors()->first());
            }
            if ($request->id) {
                $id = $request->id;
                $image = News_image::find($id);
                if ($image) {
                    Storage::disk('public')->delete($image->getRawOriginal('other_image'));
                    $image->delete();
                }
                return ResponseService::successResponse(__('Image deleted!'));
            } else {

                return ResponseService::errorResponse(__('Please fill all the data and submit!'), null, config('constants.RESPONSE_CODE.VALIDATION_ERROR'), null);
            }
        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, 'API Controller -> deleteNewsImages');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function deleteNews(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return ResponseService::validationError($validator->errors()->first());
        }

        $news = News::find($request->id);

        if (!$news) {
            return ResponseService::errorResponse('News not found');
        }

        // Delete video file
        if ($news->content_type === 'video_upload' && !empty($news->content_value)) {
            if (Storage::disk('public')->exists($news->content_value)) {
                Storage::disk('public')->delete($news->content_value);
            }
        }

        // Delete main image
        $imagePath = $news->getRawOriginal('image');
        if (!empty($imagePath) && Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }

        // Delete gallery images
        $data_image = News_image::where('news_id', $news->id)->get();

        foreach ($data_image as $row) {
            $otherImage = $row->getRawOriginal('other_image');

            if (!empty($otherImage) && Storage::disk('public')->exists($otherImage)) {
                Storage::disk('public')->delete($otherImage);
            }

            $row->delete();
        }

        $news->delete();

        return ResponseService::successResponse(__('News deleted!'));

    } catch (\Exception $e) {
        ResponseService::logErrorResponse($e, 'API Controller -> deleteNews');
        return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
    }
}


    // public function updateNews($request)
    // {
    //     $languageId = $this->resolveLanguageId($request);
    //     $news_id = $request->news_id;
    //     $news = News::find($news_id);
    //     if ($news) {
    //         if ($news->user_id == Auth::user()->id) {
    //             $slug = generateUniqueSlug($request->slug);
    //             $existingSlug = News::where('slug', $slug)->where('id', '!=', $news_id)->exists();
    //             if ($existingSlug) {
    //                 $response = [
    //                     'error' => true,
    //                     'message' => 'The slug is already in use. Please choose another.',
    //                 ];
    //                 return $response;
    //             }
    //             $data = [];
    //             $data['user_id'] = Auth::user()->id;
    //             if ($request->category_id) {
    //                 $category_id = $request->category_id;
    //                 $data['category_id'] = $category_id;
    //             }
    //             if ($request->subcategory_id) {
    //                 $subcategory_id = $request->subcategory_id;
    //                 $data['subcategory_id'] = $subcategory_id ?? 0;
    //             } else {
    //                 $data['subcategory_id'] = 0;
    //             }
    //             if ($request->tag_id) {
    //                 $tag_id = $request->tag_id;
    //                 $data['tag_id'] = $tag_id;
    //             }
    //             if ($request->title) {
    //                 $title = $request->title;
    //                 $data['title'] = $title;
    //             }
    //             $data['date'] = $this->toDateTime;
    //             $data['published_date'] = $request->published_date ?? null;
    //             if ($request->description) {
    //                 $description = $request->description;
    //                 $data['description'] = $description;
    //             }

    //             if ($request->summarized_description) {
    //                 $data['summarized_description'] = $request->summarized_description;
    //             }

    //             if ($request->meta_description) {
    //                 $meta_description = $request->meta_description;
    //                 $data['meta_description'] = $meta_description;
    //             }

    //             if ($request->meta_title) {
    //                 $meta_title = $request->meta_title;
    //                 $data['meta_title'] = $meta_title;
    //             }

    //             if ($request->meta_keyword) {
    //                 $meta_keyword = $request->meta_keyword;
    //                 $data['meta_keyword'] = $meta_keyword;
    //             }

    //             if ($request->slug) {
    //                 $slug = $request->slug;
    //                 $data['slug'] = $slug;
    //             }

    //             if ($request->show_till) {
    //                 $show_till = $request->show_till;
    //                 $data['show_till'] = $show_till;
    //             }
    //             if ($languageId) {
    //                 // $language_id = $request->language_id;
    //                 $data['language_id'] = $languageId;
    //             }
    //             if ($request->location_id) {
    //                 $location_id = $request->location_id;
    //                 $data['location_id'] = $location_id;
    //             }
    //             // if($request->is_draft){
    //             $data['is_draft'] = $request->is_draft;
    //             // }

    //             $content_type = $request->content_type;

    //             if ($content_type == 'standard_post') {
    //                 $content_value = '';
    //             } elseif ($content_type == 'video_youtube') {
    //                 $content_value = $request->input('content_data');
    //             } elseif ($content_type == 'video_other') {
    //                 $content_value = $request->input('content_data');
    //             } elseif ($content_type == 'video_upload') {
    //                 $file = $request->file('content_data');
    //                 if ($request->hasFile('content_data') && $file->isValid()) {
    //                     if (!empty($news->content_value) && Storage::disk('public')->exists($news->content_value)) {
    //                         Storage::disk('public')->delete($news->content_value);
    //                     }

    //                     $content_value = $request->file('content_data')->store('news_video', 'public');
    //                 } else {
    //                     $content_value = $news->content_value;
    //                 }
    //             }

    //             $news->content_type = $content_type;
    //             $news->content_value = $content_value;
    //             if ($request->hasFile('image')) {
    //                 $news->image = compressAndReplace($request->file('image'), 'news', $news->getRawOriginal('image'));
    //             }

    //             $news->update($data);
    //             if ($request->file('ofile')) {
    //                 foreach ($request->file('ofile') as $file) {
    //                     $newFile = new News_image();
    //                     $newFile->news_id = $news->id;
    //                     $newFile->other_image = compressAndUpload($file, 'news');
    //                     $newFile->save();
    //                 }
    //             }
    //             // $response = [
    //             //     'error' => false,
    //             //     'message' => 'News Updated Successfully',
    //             // ];
    //             return ResponseService::successResponse(__('News Updated Successfully'));
    //         } else {
    //             // $response = [
    //             //     'error' => true,
    //             //     'message' => 'You do not have permission to manage this news.',
    //             // ];
    //             return ResponseService::errorResponse(__('You do not have permission to manage this news.'), null, config('constants.RESPONSE_CODE.VALIDATION_ERROR'), null);
    //         }
    //     } else {
    //         // $response = [
    //         //     'error' => true,
    //         //     'message' => 'No Data Found',
    //         // ];
    //         // ResponseService::logErrorResponse($e, 'API Controller -> updateNews');
    //         return ResponseService::errorResponse(__('No Data Found'), null, config('constants.RESPONSE_CODE.VALIDATION_ERROR'), null);
    //     }
    //     // return $response;
    // }

    public function updateNews(Request $request)
    {
        $languageId = $this->resolveLanguageId($request);
        $news = News::find($request->news_id);

        if (!$news) {
            return ResponseService::errorResponse(
                __('No Data Found'),
                null,
                config('constants.RESPONSE_CODE.NOT_FOUND')
            );
        }

        if ($news->user_id !== Auth::id()) {
            return ResponseService::errorResponse(
                __('You do not have permission to manage this news.'),
                null,
                config('constants.RESPONSE_CODE.NOT_AUTHORIZED')
            );
        }

        // Generate unique slug if provided
        if ($request->filled('slug')) {
            $slug = generateUniqueSlug($request->slug);

            $slugExists = News::where('slug', $slug)
                ->where('id', '!=', $news->id)
                ->exists();

                if ($slugExists) {
                    return response()->json([
                    'error' => true,
                    'message' => 'The slug is already in use. Please choose another.',
                ]);
            }

            $news->slug = $slug;
        }

        // Prepare update data
        $data = $request->only([
            'category_id',
            'subcategory_id',
            'tag_id',
            'title',
            'description',
            'summarized_description',
            'meta_description',
            'meta_title',
            'meta_keyword',
            'published_date',
            'show_till',
            'location_id',
            'is_draft',
            'is_short_news'
        ]);

        $data['subcategory_id'] = $request->subcategory_id ?? 0;
        $data['language_id'] = $languageId;
        $data['user_id'] = Auth::id();
        $data['date'] = now();

        // dd($news->toArray());
        // Handle Content Type
        // $this->handleContentType($request, $news);

        // Handle Main Image
        if ($request->hasFile('image')) {
            $news->image = compressAndReplace(
                $request->file('image'),
                'news',
                $news->getRawOriginal('image')
            );
        }

        $news->update($data);

        // Handle Multiple Images
        if ($request->hasFile('ofile')) {
            foreach ($request->file('ofile') as $file) {
                News_image::create([
                    'news_id' => $news->id,
                    'other_image' => compressAndUpload($file, 'news')
                ]);
            }
        }

        return ResponseService::successResponse(__('News Updated Successfully'));
    }

    public function createNews($request)
    {
        $languageId = $this->resolveLanguageId($request);
        if (Auth::user()->is_author == 0) {
            return ResponseService::errorResponse('You do not have permission to create news.');
        }
        $slug = generateUniqueSlug($request->slug);
        $existingSlug = News::where('slug', $slug)->exists();
        if ($existingSlug) {
            $response = [
                'error' => true,
                'message' => 'The slug is already in use. Please choose another.',
            ];
            return $response;
        }

        $news = new News();
        $content_type = $request->content_type;
        if ($content_type == 'standard_post') {
            $content_value = '';
        } elseif ($content_type == 'video_youtube') {
            $content_value = $request->input('content_data');
        } elseif ($content_type == 'video_other') {
            $content_value = $request->input('content_data');
        } elseif ($content_type == 'video_upload') {
            $file = $request->file('content_data');
            if ($request->hasFile('content_data') && $file->isValid()) {
                $content_value = $request->file('content_data')->store('news_video', 'public');
            } else {
                $content_value = '';
            }
        }
        if ($request->hasFile('image')) {
            $news->image = compressAndUpload($request->file('image'), 'news');
        }


        $news->language_id = $languageId;
        $news->category_id = $request->category_id ?? 0;
        $news->subcategory_id = $request->subcategory_id ?? 0;
        $news->tag_id = $request->tag_id ?? '';
        $news->title = $request->title;
        $news->slug = $request->slug;
        $news->date = $this->toDateTime;
        $news->published_date = $request->published_date ?? null;
        $news->description = $request->description ?? null;
        $news->summarized_description = $request->summarized_description ?? null;
        $news->status = $request->status;
        $news->content_type = $content_type;
        $news->content_value = $content_value;
        $news->user_id = Auth::user()->id;
        $news->show_till = $request->show_till ?? '';
        $news->location_id = $request->location_id ?? 0;
        $news->meta_title = $request->meta_title ?? '';
        $news->meta_keyword = $request->meta_keyword ?? '';
        $news->meta_description = $request->meta_description ?? '';
        $news->admin_id = 0;
        $news->status = 0;
        $news->is_draft = $request->is_draft ?? 0; // 0-no, 1-yes
        $news->is_short_news = $request->is_short_news ?? 0; // 0-no, 1-yes
        $news->save();

        $id = $news->id;
        if ($request->file('ofile')) {
            foreach ($request->file('ofile') as $file) {
                $newFile = new News_image();
                $newFile->news_id = $id;
                $newFile->other_image = compressAndUpload($file, 'news');
                $newFile->save();
            }
        }
        $response = [
            'error' => false,
            'message' => 'News added Successfully',
        ];
        return $response;
    }

    public function setNews(Request $request)
    {
        try {

            if ($request->is_draft == 1) {

                $validator = Validator::make($request->all(), [
                    'action_type' => 'required',
                    'news_id' => 'required_if:action_type,2',
                    'title' => 'required|string|max:255',
                    'slug' => 'required|string|max:255',
                    'published_date' => 'nullable',
                    'is_draft' => 'required|boolean',
                ]);

            } else {

                $validator = Validator::make($request->all(), [
                    'action_type' => 'required',
                    'news_id' => 'required_if:action_type,2',
                    'title' => 'required|string|max:255',
                    'slug' => 'required|string|max:255',
                    'published_date' => 'nullable',
                    'description' => 'nullable',
                    'is_draft' => 'required|boolean',
                    'image' => 'nullable|image:png,jpg,jpeg,svg,webp',
                ]);
            }

            if ($validator->fails()) {
                ResponseService::validationError($validator->errors()->first());
            }

            if (Auth::user()->is_author == 1) {
                if ($request->action_type && $request->action_type == '2') {
                    $response = $this->updateNews($request);
                    // dd($request->action_type);
                    return $response;
                } else {
                    $response = $this->createNews($request);
                    return $response;
                }
            }
            ResponseService::errorResponse('You do not have permission to manage news.');

        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, 'API Controller -> setNews');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function getQuestionResult(Request $request)
    {
        try {

            $languageId = $this->resolveLanguageId($request);
            $offset = $request->offset ?? 0;
            $limit = $request->limit ?? 10;
            $user_id = Auth::user()->id;
            $answeredQuestionIds = SurveyResult::where('user_id', $user_id)->pluck('question_id')->toArray();

            $where = [
                'status' => '1',
                'language_id' => $languageId,
            ];
            if ($request->has('question_id')) {
                $where['question_id'] = $request->question_id;
            }

            if (!empty($answeredQuestionIds)) {
                $where[] = ['id', 'NOT IN', $answeredQuestionIds];
            }
            $res = SurveyQuestion::with(['surveyOptions'])->withCount('surveyResult')->where(function ($q) use ($where) {
                $q->where('status', $where['status'])->where('language_id', $where['language_id']);
                if (!empty($where['id'])) {
                    $q->whereNotIn('id', $where['id'][2]);
                }
                if (isset($where['question_id'])) {
                    $q->where('id', $where['question_id']);
                }
            });

            $total = $res->clone()->count();
            if ($total == 0) {
                return ResponseService::errorResponse(__('No Data Found'), null, config('constants.RESPONSE_CODE.NOT_FOUND'), null);
            }
            $questions = $res->clone()->orderByDesc('id')->limit($limit)->offset($offset)->get();
            foreach ($questions as $row) {
                $totalUserResponses = SurveyResult::where('question_id', $row->id)->count();
                // Ensure the surveyOptions relationship is loaded
                $row->load([
                    'surveyOptions' => function ($query) {
                        $query->withCount('result');
                    }
                ]);
                // Calculate and set the percentage on each survey option
                $row->surveyOptions->each(function ($option) use ($totalUserResponses) {
                    $option->percentage = $totalUserResponses != 0 ? ($option->result_count * 100) / $totalUserResponses : 0;
                });
            }

            return ResponseService::successResponse(__('Data fetched successfully'), [
                'total' => $total,
                'data' => $questions,
            ]);

        } catch (Exception $e) {

            ResponseService::logErrorResponse($e, 'API Controller -> getQuestionResult');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function setQuestionResult(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'question_id' => ['required', 'numeric'],
                'option_id' => ['required', 'numeric'],
            ]);
            if ($validator->fails()) {
                return ResponseService::validationError($validator->errors()->first());
            }
            $survey_result = new SurveyResult();
            $survey_result->user_id = Auth::user()->id;
            $survey_result->question_id = $request->question_id;
            $survey_result->option_id = $request->option_id;
            $survey_result->save();

            $res = SurveyOption::find($request->option_id);
            $counter = $res->counter + 1;
            $res->counter = $counter;
            $res->save();

            return ResponseService::successResponse(__('Data inserted successfully'));
        } catch (Exception $e) {

            ResponseService::logErrorResponse($e, 'API Controller -> setQuestionResult');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function getQuestion(Request $request)
    {
        try {
            $languageId = $this->resolveLanguageId($request);
            $user_id = Auth::user()->id;
            $answeredQuestionIds = SurveyResult::where('user_id', $user_id)->pluck('question_id')->toArray();

            $data = SurveyQuestion::select('id', 'question', 'status', 'language_id')->with('surveyOptions:id,options,counter,question_id')->where(['status' => 1, 'language_id' => $languageId]);
            if (!empty($answeredQuestionIds)) {
                $data = $data->whereNotIn('id', $answeredQuestionIds);
            }
            $total = $data->clone()->count('id');
            if ($total == 0) {
                return ResponseService::errorResponse(__('No Data Found'), null, config('constants.RESPONSE_CODE.NOT_FOUND'), null);
            }

            $offset = $request->offset ?? 0;
            $limit = $request->limit ?? 10;
            $res = $data->clone()->orderByDesc('id')->limit($limit)->offset($offset)->get();

            return ResponseService::successResponse(__('Data fetched successfully'), [
                'total' => $total,
                'data' => $res,
            ]);

        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, 'API Controller -> getQuestion');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function getBookmark(Request $request)
    {
        try {

            $language_id = $this->resolveLanguageId($request);
            $user_id = Auth::user()->id;
            $offset = $request->offset ?? 0;
            $limit = $request->limit ?? 10;

            // $news = DB::table('tbl_bookmark as b')
            //     ->select('b.*', 'n.category_id', 'c.category_name', 'n.subcategory_id', 'n.language_id', 'n.title', 'n.slug', 'n.date', 'n.published_date', 'n.show_till', 'n.is_comment', 'n.tag_id', 'n.content_type', 'n.content_value', 'n.image', 'n.description')
            //     ->join('tbl_news as n', 'b.news_id', '=', 'n.id')
            //     ->join('tbl_category as c', 'c.id', '=', 'n.category_id')
            //     ->where(function ($query) {
            //         $query->where('n.show_till', '>=', $this->toDate)->orWhere('n.show_till', '0000-00-00');
            //     })->where('b.user_id', $user_id)->where('n.status', 1)->where('n.published_date', '<=', $this->toDate)->where('n.language_id', $language_id);

            // news query with published_date null constraint
            $news = DB::table('tbl_bookmark as b')
                ->select('b.*', 'n.category_id', 'c.category_name', 'n.subcategory_id', 'n.language_id', 'n.title', 'n.slug', 'n.date', 'n.published_date', 'n.show_till', 'n.is_comment', 'n.tag_id', 'n.content_type', 'n.content_value', 'n.image', 'n.description')
                ->join('tbl_news as n', 'b.news_id', '=', 'n.id')
                ->join('tbl_category as c', 'c.id', '=', 'n.category_id')
                ->where(function ($query) {
                    $query->where('n.show_till', '>=', $this->toDate)->orWhere('n.show_till', '0000-00-00');
                })
                ->where('b.user_id', $user_id)->where('n.status', 1)
                ->where(function ($q) {
                    $q->where(function ($subq) {
                        $subq->whereNotNull('n.published_date')
                            ->where('n.published_date', '<=', $this->toDate);
                    })->orWhere(function ($subq) {
                        $subq->whereNull('n.published_date')
                            ->whereDate('n.created_at', '<=', $this->toDate);
                    });
                })
                ->where('n.language_id', $language_id);

            $total = $news->clone()->count();

            if ($total == 0) {
                return ResponseService::errorResponse(__('No Data Found'), null, config('constants.RESPONSE_CODE.NOT_FOUND'), null);
            }

            $data = $news->clone()->limit($limit)->offset($offset)->orderBy('id', 'DESC')->get();
            foreach ($data as $item) {
                //get other data (total_like, total_views etc..)
                $item = $this->getNewsData($item, $item->news_id);

                if (($item->image) && strpos($item->image, 'news/') === false) {
                    $image = 'news/' . $item->image;
                } else {
                    $image = $item->image;
                }
                $item->image = ($item->image) && Storage::disk('public')->exists($image) ? url(Storage::url($image)) : '';

                if ($item->content_type == 'video_upload') {
                    $item->content_type = Storage::url('public/images/news/' . $item->content_value);
                }
                $item->image_data = News_image::where('news_id', $item->news_id)->get();
            }

            return ResponseService::successResponse(__('Data fetched successfully'), [
                'total' => $total,
                'data' => $data,
            ]);

        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, 'API Controller -> getBookmark');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function setBookmark(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'news_id' => ['required', 'numeric'],
                'status' => ['required', 'numeric'],
            ]);
            if ($validator->fails()) {
                return ResponseService::validationError($validator->errors()->first());
            }
            $user_id = Auth::user()->id;
            $news_id = $request->news_id;
            $status = $request->status;
            if ($status == '1') {
                $data = Bookmark::where('user_id', $user_id)->where('news_id', $news_id)->count('id');
                if ($data) {

                    return ResponseService::errorResponse(__('Already bookmark'), null, config('constants.RESPONSE_CODE.VALIDATION_ERROR'), null);
                }
                // else {
                    Bookmark::create([
                        'user_id' => $user_id,
                        'news_id' => $news_id,
                    ]);

                return ResponseService::successResponse(__('Bookmark successfully'));
                // }
            } elseif ($status == '0') {
                Bookmark::where('user_id', $user_id)->where('news_id', $news_id)->delete();

                return ResponseService::successResponse(__('Bookmark removed successfully'));
            }
        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, 'API Controller -> setBookmark');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function setFlag(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'comment_id' => ['required', 'numeric'],
                'news_id' => ['required', 'numeric'],
                'message' => 'required',
            ]);
            if ($validator->fails()) {

                return ResponseService::validationError($validator->errors()->first());
            }
            // $commnt_flag = new CommentsFlag();
            // $commnt_flag->comment_id = $request->comment_id;
            // $commnt_flag->user_id = Auth::user()->id;
            // $commnt_flag->news_id = $request->news_id;
            // $commnt_flag->message = $request->message;
            // $commnt_flag->status = 1;
            // $commnt_flag->date = $this->toDateTime;
            // $commnt_flag->save();
            CommentsFlag::create([
                'comment_id' => $request->comment_id,
                'user_id' => Auth::user()->id,
                'news_id' => $request->news_id,
                'message' => $request->message,
                'status' => 1,
                'date' => $this->toDateTime,
            ]);

            return ResponseService::successResponse(__('Flag successfully'));
        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, 'API Controller -> setFlag');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function setCommentLikeDislike(Request $request)
    {
        try {
            $language_id = $this->resolveLanguageId($request);
            $validator = Validator::make($request->all(), [
                // 'language_id' => ['required', 'numeric'],
                'comment_id' => ['required', 'numeric'],
                'status' => ['required', 'numeric'],
            ]);
            if ($validator->fails()) {
                return ResponseService::validationError($validator->errors()->first());
            }
            $user_id = Auth::user()->id;
            $comment_id = $request->comment_id;
            $status = $request->status;
            if ($status != '0') {
                $comment_like = CommentsLike::where('comment_id', $comment_id)->where('user_id', $user_id)->first();
                if (!empty($comment_like)) {
                    $comment_like->status = $status;
                    $comment_like->save();
                } else {
                    $comment_like = new CommentsLike();
                    $comment_like->user_id = $user_id;
                    $comment_like->comment_id = $comment_id;
                    $comment_like->status = $status;
                    $comment_like->save();
                }
                $insert_id = $comment_like->id;
                if ($status == '1') {
                    $res_comment = CommentsLike::find($insert_id);
                    if ($res_comment) {
                        $comment_id1 = $res_comment->comment_id;
                        $res_comment1 = Comments::find($comment_id1);
                        if ($res_comment1) {
                            $old_user_id = $res_comment1->user_id;
                            // $res1 = User::find($old_user_id);

                            // getting token from token table by user_id

                            $token = Token::where('user_id', $old_user_id)->first();
                            // Log::info('token', $token);
                            if (!empty($token)) {
                                $get_name = Auth::user()->name;
                                $fcmMsg = [
                                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                                    'type' => 'comment_like',
                                    'language_id' => $language_id,
                                    'title' => 'Like in your comment ' . $res_comment1->message . ' by ' . $get_name,
                                    'body' => 'Like in your comment ' . $res_comment1->message . ' by ' . $get_name,
                                    'sound' => 'default',
                                ];
                                if ($token->token) {
                                    $devicetoken[] = $token->token;
                                    send_notification($fcmMsg, $language_id, 0, $devicetoken);
                                }

                                // new approach
                                $comment_notification = CommentNotification::create([
                                    'master_id' => $insert_id,
                                    'user_id' => $old_user_id,
                                    'sender_id' => $user_id,
                                    'type' => 'comment_like',
                                    'message' => 'Like in your comment ' . $res_comment1->message . ' by ' . $get_name,
                                    'date' => $this->toDateTime,
                                ]);
                            }
                        }
                    }
                }
            } else {
                CommentsLike::where('comment_id', $comment_id)->where('user_id', $user_id)->delete();
            }
            $res = Comments::where('id', $comment_id)->first();
            $news_id = $res->news_id ?? 0;
            $response = $this->getCommentData('setCommentLikeDislike', $user_id, $news_id);
            // return response()->json($response);
            return ResponseService::successResponse(__('Comment like/dislike successfully'), $response); 

        } catch (Exception $e) {
            // $response = [
            //     'error' => true,
            //     'message' => $e->getMessage()
            // ];
            // logresponse
            ResponseService::logErrorResponse($e, 'API Controller -> setCommentLikeDislike');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function deleteComment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'comment_id' => ['required', 'numeric'],
            ]);
            if ($validator->fails()) {
                return ResponseService::validationError($validator->errors()->first());
            }
            $id = $request->comment_id;
            $comment = Comments::find($id);
            if ($comment) {
                if ($comment->user_id == Auth::user()->id) {
                    // for remove sub comment data
                    $sub_comment = Comments::select('id')->where('parent_id', $id)->get();
                    if (!$sub_comment->isEmpty()) {
                        foreach ($sub_comment as $row) {
                            Comments::find($row->id)->delete();
                        }
                    }
                }
                $comment->delete();
                return ResponseService::successResponse(__('Comment deleted!'));
            }
            // else {

            return ResponseService::errorResponse(__('No Data Found'), null, config('constants.RESPONSE_CODE.NOT_FOUND'), null);
            // }
        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, 'API Controller -> deleteComment');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function setComment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'news_id' => ['required', 'numeric'],
                'message' => 'required',
            ]);
            if ($validator->fails()) {
                return ResponseService::validationError($validator->errors()->first());
            }
            $user_id = Auth::user()->id;
            $parent_id = $request->parent_id ?? 0;
            $news_id = $request->news_id;
            $message = $request->message;

            $comment = Comments::create([
                'user_id' => $user_id,
                'parent_id' => $parent_id,
                'news_id' => $news_id,
                'message' => $message,
                'status' => 1,
                'date' => $this->toDateTime,
            ]);
            $insert_id = $comment->id;
            if ($parent_id) {
                $res = Comments::find($parent_id);
                if (!empty($res)) {
                    $old_user_id = $res->user_id;
                    $user = User::find($old_user_id);
                    if (!empty($user)) {
                        $fcmMsg = [
                            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                            'type' => 'comment',
                            'news_id' => $news_id,
                            'title' => 'Reply in your comment ' . $res->message . ' by ' . $user->name,
                            'body' => 'Reply in your comment ' . $res->message . ' by ' . $user->name,
                            'sound' => 'default',
                        ];

                        if ($user->fcm_id) {
                            $devicetoken[] = $user->fcm_id;
                            send_notification($fcmMsg, 0, 0, $devicetoken);
                        }

                        CommentNotification::create([
                            'master_id' => $insert_id,
                            'user_id' => $old_user_id,
                            'sender_id' => $user_id,
                            'type' => 'comment',
                            'message' => 'Reply in your comment ' . $res->message . ' by ' . $user->name,
                            'date' => $this->toDateTime,
                        ]);
                    }
                }
            }
            $response = $this->getCommentData('setComment', $user_id, $news_id);
        } catch (Exception $e) {
            $response = [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
        return response()->json($response);
    }

    public function setBreakingNewsView(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'breaking_news_id' => ['required', 'numeric'],
            ]);
            if ($validator->fails()) {
                return ResponseService::validationError($validator->errors()->first());
            }
            $breaking_news_id = $request->breaking_news_id;
            $views_auth_mode = Settings::where('type', 'views_auth_mode')->value('message') ?? '1';
            $user_id = auth()->check() ? auth()->id() : null;

            if ($views_auth_mode == '1' && !$user_id) {
                // return response()->json([
                //     'error' => true,
                //     'message' => 'Authentication required to view this news.',
                // ]);
                return ResponseService::errorResponse(__('Authentication required to view this news.'), null, config('constants.RESPONSE_CODE.NOT_AUTHORIZED'), null);
            }

            if ($user_id) {
                $alreadyViewed = BreakingNewsView::where('user_id', $user_id)
                    ->where('breaking_news_id', $breaking_news_id)
                    ->exists();

                if ($alreadyViewed) {
                    // return response()->json([
                    //     'error' => true,
                    //     'message' => 'Breaking News already viewed by this user',
                    // ]);
                    return ResponseService::errorResponse(__('Breaking News already viewed by this user'), null, config('constants.RESPONSE_CODE.SUCCESS'), null);
                }

                BreakingNewsView::create([
                    'user_id' => $user_id,
                    'breaking_news_id' => $breaking_news_id,
                ]);
            } else {
                BreakingNewsView::create([
                    'user_id' => null,
                    'breaking_news_id' => $breaking_news_id,
                ]);
            }

            // return response()->json([
            //     'error' => false,
            //     'message' => 'Breaking News view added successfully.',
            // ]);
            return ResponseService::successResponse(__('Breaking News view added successfully.'));
        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, 'API Controller -> setBreakingNewsView');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function setNewsView(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'news_id' => ['required', 'numeric'],
            ]);

            if ($validator->fails()) {
                return ResponseService::validationError($validator->errors()->first());
            }

            $news_id = $request->news_id;
            $views_auth_mode = Settings::where('type', 'views_auth_mode')->value('message') ?? '1';
            $user_id = auth()->check() ? auth()->id() : null;

            if ($views_auth_mode == '1' && !$user_id) {
                return ResponseService::errorResponse(__('Authentication required to view this news.'), null, config('constants.RESPONSE_CODE.NOT_AUTHORIZED'), null);
            }

            if ($user_id) {
                $alreadyViewed = News_view::where('user_id', $user_id)
                    ->where('news_id', $news_id)
                    ->exists();

                if ($alreadyViewed) {
                    return ResponseService::errorResponse(__('News already viewed by this user'), null, config('constants.RESPONSE_CODE.SUCCESS'), null);
                }

                News_view::create([
                    'user_id' => $user_id,
                    'news_id' => $news_id,
                ]);
            } else {
                News_view::create([
                    'user_id' => null,
                    'news_id' => $news_id,
                ]);
            }

            return ResponseService::successResponse(__('News view added successfully.'));
        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, 'API Controller -> setNewsView');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function getLike(Request $request)
    {
        try {

            $language_id = $this->resolveLanguageId($request);
            $user_id = Auth::user()->id;
            $offset = $request->offset ?? 0;
            $limit = $request->limit ?? 10;

            // $news = DB::table('tbl_news_like as l')
            //     ->select('l.*', 'n.category_id', 'c.category_name', 'n.title', 'n.slug', 'n.date', 'n.published_date', 'n.show_till', 'n.tag_id', 'n.content_type', 'n.content_value', 'n.image', 'n.description')
            //     ->join('tbl_news as n', 'n.id', '=', 'l.news_id')
            //     ->join('tbl_category as c', 'c.id', '=', 'n.category_id')
            //     ->where(function ($query) {
            //         $query->where('n.show_till', '>=', $this->toDate)->orWhere('n.show_till', '0000-00-00');
            //     })->where('l.user_id', $user_id)->where('l.status', 1)->where('n.published_date', '<=', $this->toDate)->where('n.language_id', $language_id);

            // news query with published_date null constraint
            $news = DB::table('tbl_news_like as l')
                ->select('l.*', 'n.category_id', 'c.category_name', 'n.title', 'n.slug', 'n.date', 'n.published_date', 'n.show_till', 'n.tag_id', 'n.content_type', 'n.content_value', 'n.image', 'n.description')
                ->join('tbl_news as n', 'n.id', '=', 'l.news_id')
                ->join('tbl_category as c', 'c.id', '=', 'n.category_id')
                ->where(function ($query) {
                    $query->where('n.show_till', '>=', $this->toDate)->orWhere('n.show_till', '0000-00-00');
                })
                ->where('l.user_id', $user_id)->where('l.status', 1)
                ->where(function ($q) {
                    $q->where(function ($subq) {
                        $subq->whereNotNull('n.published_date')
                            ->where('n.published_date', '<=', $this->toDate);
                    })->orWhere(function ($subq) {
                        $subq->whereNull('n.published_date')
                            ->whereDate('n.created_at', '<=', $this->toDate);
                    });
                })
                ->where('n.language_id', $language_id);



            $total = $news->clone()->count();

            if ($total == 0) {
                return ResponseService::errorResponse(__('No Data Found'), null, config('constants.RESPONSE_CODE.NOT_FOUND'), null);
            }
                $data = $news->clone()->limit($limit)->offset($offset)->orderBy('l.id', 'DESC')->get();

                foreach ($data as $item) {
                    //get other data (total_like, total_views etc..)
                    $item = $this->getNewsData($item, $item->news_id);
                    $item->image_data = News_image::where('news_id', $item->news_id)->get();
                    if (($item->image) && strpos($item->image, 'news/') === false) {
                        $image = 'news/' . $item->image;
                    } else {
                        $image = $item->image;
                    }
                    $item->image = ($item->image) && Storage::disk('public')->exists($image) ? url(Storage::url($image)) : '';
                }

                return ResponseService::successResponse(__('Data fetched successfully'), [
                    'total' => $total,
                    'data' => $data,
                ]);

        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, 'API Controller -> getLike');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function setLikeDislike(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'news_id' => ['required', 'numeric'],
                'status' => ['required', 'numeric'],
            ]);
            if ($validator->fails()) {
                return ResponseService::validationError($validator->errors()->first());
            }
            $user_id = Auth::user()->id;
            $news_id = $request->news_id;
            $status = $request->status;
            if ($status != '0') {
                $news_like = News_like::where('news_id', $news_id)->where('user_id', $user_id)->first();
                if ($news_like) {
                    $news_like->status = $status;
                    $news_like->save();
                } else {
                    $news_like = new News_like();
                    $news_like->status = $status;
                    $news_like->user_id = $user_id;
                    $news_like->news_id = $news_id;
                    $news_like->save();
                }
            } else {
                News_like::where('news_id', $news_id)->where('user_id', $user_id)->delete();
            }

            return ResponseService::successResponse(__('Updated successfully!'));
        } catch (Exception $e) {

            ResponseService::logErrorResponse($e, 'API Controller -> setLikeDislike');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }

    }

    public function deleteUserNotification(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => ['required'],
            ]);
            if ($validator->fails()) {
                return ResponseService::validationError($validator->errors()->first());
            }
            $ids = $request->id;
            CommentNotification::whereIn('id', explode(',', $ids))->delete();

            return ResponseService::successResponse(__('Notification deleted'));
        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, 'API Controller -> deleteUserNotification');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function getUserNotification(Request $request)
    {
        try {
            $user_id = Auth::user()->id;
            $offset = $request->offset ?? 0;
            $limit = $request->limit ?? 10;
            $res = CommentNotification::where('user_id', $user_id);
            $total = $res->clone()->count('id');
            if ($total == 0) {
                return ResponseService::errorResponse(__('No Data Found'), null, config('constants.RESPONSE_CODE.NOT_FOUND'), null);
            }
            $data = $res->clone()->limit($limit)->offset($offset)->orderBy('id', 'DESC')->get();

            return ResponseService::successResponse(__('Data fetched successfully'), [
                'total' => $total,
                'data' => $data,
            ]);
        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, 'API Controller -> getUserNotification');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function setUserCategory(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'category_id' => ['required'],
            ]);
            if ($validator->fails()) {
                return ResponseService::validationError($validator->errors()->first());
            }
            $user_id = Auth::user()->id;
            $category_id = $request->category_id;
            if ($category_id == '0') {
                UserCategory::where('user_id', $user_id)->delete();
            } else {
                $user_category = UserCategory::where('user_id', $user_id)->first();
                if ($user_category) {
                    $user_category->category_id = $category_id;
                    $user_category->save();
                } else {
                    $user_category = new UserCategory();
                    $user_category->user_id = $user_id;
                    $user_category->category_id = $category_id;
                    $user_category->save();
                }
            }
            return ResponseService::successResponse(__('Updated successfully'));
        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, 'API Controller -> setUserCategory');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function registerToken(Request $request)
    {
        // log::info('registerToken', $request->all());
        try {
            $validator = Validator::make($request->all(), [
                'token' => 'required',
                'user_id' => 'nullable|exists:tbl_users,id',
                // 'device_id' => ['nullable', 'string', 'max:255'],
                // 'platform' => ['nullable', 'string', 'in:android,ios,web'],
            ]);
            if ($validator->fails()) {
                return ResponseService::validationError($validator->errors()->first());
            }

            $language_id = $this->resolveLanguageId($request);
            $token = $request->token;
            $latitude = $request->latitude ?? 0;
            $longitude = $request->longitude ?? 0;
            $userId = $request->user_id ?? null;

            // $user = User::find($userId);
            // $user->fcm_id = $token;
            // $user->save();

            $lookupKey =  ['token' => $token];

            Token::updateOrCreate($lookupKey, [
                'token' => $token,
                'user_id' => $userId,
                'language_id' => $language_id,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'platform' => $request->platform,
            ]);

            return ResponseService::successResponse(__('Device registered successfully'));
        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, 'API Controller -> registerToken');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function registerDeviceToken(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'device_id' => ['required', 'string', 'max:255'],
                'token' => ['required'],
                'language_id' => ['nullable', 'numeric'],
                'latitude' => ['nullable', 'numeric'],
                'longitude' => ['nullable', 'numeric'],
                'platform' => ['nullable', 'string', 'in:android,ios,web'],
            ]);
            if ($validator->fails()) {
                return ResponseService::validationError($validator->errors()->first());
            }

            $language_id = $request->language_id ?? 0;
            $latitude = $request->latitude ?? 0;
            $longitude = $request->longitude ?? 0;

            Token::updateOrCreate(
                ['device_id' => $request->device_id],
                [
                    'token' => $request->token,
                    'language_id' => $language_id,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'platform' => $request->platform,
                ]
            );

            return ResponseService::successResponse(__('Device registered successfully'));
        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, 'API Controller -> registerDeviceToken');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function deleteUser()
    {
        try {
            $user_id = Auth::user()->id;
            Bookmark::where('user_id', $user_id)->delete();
            BreakingNewsView::where('user_id', $user_id)->delete();
            Comments::where('user_id', $user_id)->delete();
            CommentsFlag::where('user_id', $user_id)->delete();
            CommentsLike::where('user_id', $user_id)->delete();
            CommentNotification::where('user_id', $user_id)->delete();
            News_like::where('user_id', $user_id)->delete();
            News_view::where('user_id', $user_id)->delete();
            SurveyResult::where('user_id', $user_id)->delete();
            UserCategory::where('user_id', $user_id)->delete();
            User::where('id', $user_id)->delete();
            auth()->user()->tokens()->delete();
            return ResponseService::successResponse(__('User deleted successfully'));
        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, 'API Controller -> deleteUser');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'mobile' => 'nullable|integer|min:10',
            'email' => 'nullable|email|max:255',
            'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'bio' => 'nullable|string|max:255',
            'telegram_link' => 'nullable|url|max:255',
            'linkedin_link' => 'nullable|url|max:255',
            'facebook_link' => 'nullable|url|max:255',
            'whatsapp_link' => 'nullable|url|max:255',
        ]);

        if ($validator->fails()) {
            return ResponseService::validationError($validator->errors()->first());
        }
        // dd($request->all());

        try {
            $user_id = Auth::user()->id;
            $user = User::find($user_id);
            if (!empty($user)) {
                if ($request->name) {
                    $user->name = $request->name;
                }
                // if ($request->mobile) {
                    $user->mobile = $request->mobile;
                // }
                if ($request->email) {
                    $user->email = $request->email;
                }
                if ($request->hasFile('profile')) {
                    $user->profile = compressAndReplace($request->file('profile'), 'profile', $user->getRawOriginal('profile'));
                }
                $user->save();

                // if($user->is_author == 1){

                // createOrUpdate
                Author::updateOrCreate(
                    ['user_id' => $user_id],
                    [
                        'bio' => $request->bio ?? null,
                        'telegram_link' => $request->telegram_link ?? null,
                        'linkedin_link' => $request->linkedin_link ?? null,
                        'facebook_link' => $request->facebook_link ?? null,
                        'whatsapp_link' => $request->whatsapp_link ?? null,
                        // 'status' => '',
                    ]
                );
                $res = User::with('author')->where('id', $user_id)->first();
                ;
                return ResponseService::successResponse('Profile updated successfully', $res);
                // }

                //TODO

                // $res = User::where('id', $user_id)->first();

                // return ResponseService::successResponse('Profile updated successfully', $res);
            } else {
                return ResponseService::errorResponse('User not found');
            }
        } catch (Exception $e) {
            return ResponseService::errorResponse($e->getMessage());
        }
    }

    public function getUserById()
    {
        try {
            $user_id = Auth::user()->id;
            $res = User::with('user_category', 'author')->where('id', $user_id)->first();
            if ($res) {

                return ResponseService::successResponse(__('Data fetched successfully'), $res);
            }

            return ResponseService::errorResponse(__('No Data Found'), null, config('constants.RESPONSE_CODE.NOT_FOUND'), null);

        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, 'API Controller -> getUserById');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function userSignup(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'firebase_id' => 'required',
            ]);
            if ($validator->fails()) {
                return ResponseService::validationError($validator->errors()->first());
            }
            $firebase_id = $request->firebase_id;
            $type = $request->type;
            $user = User::where('firebase_id', $firebase_id)->first();

            if (!$user) {
                // Create a new user if not found
                $user = new User();
                $user->firebase_id = $firebase_id;
                $user->name = $request->name ?? '';
                $user->type = $type;
                $user->email = $request->email ?? '';
                $user->mobile = $request->mobile ?? '';
                $user->profile = $request->profile ?? '';
                $user->fcm_id = $request->fcm_id ?? '';
                $user->status = $request->status ?? 1;
                $user->date = $this->toDateTime;
                $user->is_author = 0; // no author
                $user->save();
                $user->is_login = '0'; // for web
                $message = 'User Registered successfully';


                $userData = User::with('author')->where('id', $user->id)->first();

                // Generate and return token
                $userData['token'] = $user->createToken('MyApp')->plainTextToken;

                return ResponseService::successResponse($message, $userData);
            } elseif ($user->status == 1) {
                // Update user's FCM ID if provided
                if ($request->fcm_id) {
                    $user->fcm_id = $request->fcm_id;
                    $user->save();
                }
                $user->is_login = '1'; // for web
                $message = 'Successfully logged in';

                $userData = User::with('author')->where('id', $user->id)->first();

                // Generate and return token
                $userData['token'] = $user->createToken('MyApp')->plainTextToken;

                return ResponseService::successResponse($message, $userData);


            } else {
                return ResponseService::errorResponse('User is deactivated.', null, null, null, 401);
            }

        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, 'API Controller -> userSignup');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function getAdSpaceNewsDetails(Request $request)
    {
        try {
            $languageId = $this->resolveLanguageId($request);
            $data = [];
            $res = AdSpaces::where('language_id', $languageId)->where('status', 1);
            $ad_space = $res->clone()->where('ad_space', 'news_details_top')->first();
            if (!empty($ad_space)) {
                $ad_space->position = 'top';
                $data['ad_spaces_top'] = $ad_space;
            }
            $ad_space1 = $res->clone()->where('ad_space', 'news_details_bottom')->first();
            if (!empty($ad_space1)) {
                $ad_space1->position = 'bottom';
                $data['ad_spaces_bottom'] = $ad_space1;
            }

            if (empty($data)) {

                return ResponseService::errorResponse(__('No Data Found'), null, config('constants.RESPONSE_CODE.NOT_FOUND'), null);
            }
            // else {

            return ResponseService::successResponse(__('Data fetched successfully'), $data);
            // }
        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, 'API Controller -> getAdSpaceNewsDetails');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function getBreakingNews(Request $request)
    {
        try {

            $languageId = $this->resolveLanguageId($request);
            $data = BreakingNews::where('language_id', $languageId)->withCount('breaking_news_view as total_views');
            if ($request->slug) {
                $data->where('slug', $request->slug);
            }
            $total = $data->clone()->count('id');

            if ($total == 0) {
                return ResponseService::errorResponse(__('No Data Found'), null, config('constants.RESPONSE_CODE.NOT_FOUND'), null);
            }

            $offset = $request->offset ?? 0;
            $limit = $request->limit ?? 10;
            $breakingNews = $data->clone()->skip($offset)->take($limit)->safeOrder('id', 'DESC')->get();

            return ResponseService::successResponse(__('Data fetched successfully'), [
                'total' => $total,
                'data' => $breakingNews,
            ]);
        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, 'API Controller -> getBreakingNews');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function getCommentByNews(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'news_id' => ['required', 'numeric'],
            ]);
            if ($validator->fails()) {
                return ResponseService::validationError($validator->errors()->first());
            }
            $user_id = (Auth::check()) ? Auth::user()->id : 0;
            $news_id = $request->news_id;
            $response = $this->getCommentData('getCommentByNews', $user_id, $news_id);
            return ResponseService::successResponse(__('Data fetched successfully'), $response);
        } catch (Exception $e) {

            ResponseService::logErrorResponse($e, 'API Controller -> getCommentByNews');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }


    public function getNews(Request $request)
    {
        try {
            $request['get_user_news'] = $request->get_user_news ?? 0;
            $validator = Validator::make($request->all(), [
                // 'language_id' => ['required', 'numeric'],
                // 'language_id' => ['required_if:get_user_news,0', 'numeric'],
                'get_user_news' => ['required', 'numeric']
            ]);
            if ($validator->fails()) {
                return ResponseService::validationError($validator->errors()->first());
            }

            if($request->get_user_news == 0){
                $language_id = $this->resolveLanguageId($request);
            }
            $user_id = Auth::check() ? Auth::user()->id : 0;
            $offset = $request->offset ?? 0;
            $limit = $request->limit ?? 10;
            $latitude = $request->latitude ?? 0;
            $longitude = $request->longitude ?? 0;
            $get_user_news = $request->get_user_news;


            // DB::enableQueryLog();
            $news = News::with(
                'category:id,category_name,slug',
                'sub_category:id,subcategory_name',
                'location:id,location_name,latitude,longitude',
                'images',
                'user:id,name,profile,is_author',
                'author'
            )->where('is_draft', 0);

            if ($get_user_news == 1) {
                $news->where('user_id', $user_id)->where('user_id', '!=', 0);
            } else {

                // $news->where('language_id', $language_id)->where(function ($q) {
                //     $q->where('show_till', '>=', $this->toDate)->orWhere('show_till', '0000-00-00');
                // })->where('status', 1)->where('published_date', '<=', $this->toDate);
                $news->where('language_id', $language_id)->where(function ($q) {
                    $q->where('show_till', '>=', $this->toDate)->orWhere('show_till', '0000-00-00')->orWhere('show_till', null);
                })->where('status', 1)->where(function ($q) {
                    $q->where(function ($subq) {
                        $subq->whereNotNull('published_date')
                            ->where('published_date', '<=', $this->toDate);
                    })->orWhere(function ($subq) {
                        $subq->whereNull('published_date')
                            ->whereDate('tbl_news.created_at', '<=', $this->toDate);
                        //  ->whereDate('tbl_news.created_at', '<=', $this->toDate);
                    });
                });
            }


            if ($request->id) {
                $news->where('id', $request->id);
            }
            if ($request->slug) {
                $news->where('slug', $request->slug);
            }


            if ($request->category_id) {
                // Handle multiple category IDs
                $categoryIds = explode(',', $request->category_id);
                $news->whereIn('category_id', $categoryIds);
            }
            if ($request->category_slug) {
                $category_id = Category::select('id')->where('slug', $request->category_slug)->pluck('id')->first();
                $news->where('category_id', $category_id);
            }
            if ($request->subcategory_id) {
                $news->where('subcategory_id', $request->subcategory_id);
            }
            if ($request->subcategory_slug) {
                $subcategory_id = SubCategory::select('id')->where('slug', $request->subcategory_slug)->pluck('id')->first();
                $news->where('subcategory_id', $subcategory_id);
            }
            if ($request->tag_id) {
                $tagIds = explode(',', $request->tag_id); // Convert string to array
                $news->where(function ($query) use ($tagIds) {
                    foreach ($tagIds as $tagId) {
                        $query->orWhereRaw('FIND_IN_SET(?, tag_id)', [$tagId]);
                    }
                });
            }

            if ($request->tag_slug) {
                $tag_ids = Tag::select('id')->where('slug', $request->tag_slug)->pluck('id')->first();
                // $news->whereIn('tag_id', explode(',', $tag_ids));
                $news->whereRaw('FIND_IN_SET(?, tag_id)', [$tag_ids]);
            }
            if ($request->search) {
                $search = $request->search;
                $news->where(function ($q) use ($search) {
                    $q->where('tbl_news.title', 'LIKE', "%{$search}%");
                });
            }

            // Date filtering - these filters will apply to all queries including search
            if ($request->date) {
                // $news->whereDate('published_date', $request->date);
                $news->where(function ($q) use ($request) {
                    $q->where(function ($subq) use ($request) {
                        $subq->whereNotNull('published_date')
                            ->whereDate('published_date', $request->date);
                    })->orWhere(function ($subq) use ($request) {
                        $subq->whereNull('published_date')
                            //  ->whereDate('created_at', $request->date);
                            ->whereDate('tbl_news.created_at', $request->date);
                    });
                });
            }

            // Last n days filtering
            if ($request->last_n_days && is_numeric($request->last_n_days)) {
                $startDate = Carbon::now()->subDays($request->last_n_days)->startOfDay();
                $news->whereDate('published_date', '>=', $startDate);
                $news->where(function ($q) use ($startDate) {
                    $q->where(function ($subq) use ($startDate) {
                        $subq->whereNotNull('published_date')
                            ->whereDate('published_date', '>=', $startDate);
                    })->orWhere(function ($subq) use ($startDate) {
                        $subq->whereNull('published_date')
                            //  ->whereDate('created_at', '>=', $startDate);
                            ->whereDate('tbl_news.created_at', '>=', $startDate);

                    });
                });
            }

            // Year filtering
            if ($request->year && is_numeric($request->year)) {
                // $news->whereYear('published_date', $request->year);
                $news->where(function ($q) use ($request) {
                    $q->where(function ($subq) use ($request) {
                        $subq->whereNotNull('published_date')
                            ->whereYear('published_date', $request->year);
                    })->orWhere(function ($subq) use ($request) {
                        $subq->whereNull('published_date')
                            //  ->whereYear('created_at', $request->year);
                            ->whereYear('tbl_news.created_at', $request->year);

                    });
                });
            }

            // Ensure we're not showing expired news (show_till < current date)
            $news->where(function ($q) {
                $q->where('show_till', '>=', $this->toDate)->orWhere('show_till', '0000-00-00');
            });

            // Add filter by is_comment
            if ($request->has('is_comment') && $request->is_comment != '') {
                $news->where('is_comment', $request->is_comment);
            }

            // Automatically fetch related news by tags when category_id or subcategory_id is provided
            if (($request->category_id || $request->subcategory_id) && $request->has('merge_tag') && $request->merge_tag == 1) {
                // Store the original query that has category/subcategory filters
                $originalQuery = $news->clone();

                // Create a clone of the current query to get tag IDs from matched news
                $tagQuery = $news->clone()->select('tag_id')->whereNotNull('tag_id')->where('tag_id', '!=', '');

                // Get all tag IDs from the news in the specified category/subcategory
                $tagIds = $tagQuery->pluck('tag_id')->toArray();

                // Extract all unique tag IDs from the comma-separated values
                $uniqueTagIds = [];
                foreach ($tagIds as $tagIdList) {
                    if (!empty($tagIdList)) {
                        $tagIdsArray = explode(',', $tagIdList);
                        foreach ($tagIdsArray as $tagId) {
                            if (!empty($tagId) && !in_array($tagId, $uniqueTagIds)) {
                                $uniqueTagIds[] = $tagId;
                            }
                        }
                    }
                }

                // If we found tag IDs, create a new query for tag-related news
                if (!empty($uniqueTagIds)) {
                    // Get the IDs of news from the original query to avoid duplicates
                    $originalNewsIds = $originalQuery->pluck('tbl_news.id')->toArray();

                    // Create a new query for tag-related news
                    $tagRelatedQuery = News::with('category:id,category_name,slug', 'sub_category:id,subcategory_name', 'location:id,location_name,latitude,longitude', 'images')
                        ->where('language_id', $language_id)
                        ->where(function ($q) {
                            $q->where('show_till', '>=', $this->toDate)->orWhere('show_till', '0000-00-00');
                        })
                        ->where('status', 1)
                        ->where('published_date', '<=', $this->toDate)
                        ->where(function ($query) use ($uniqueTagIds) {
                            foreach ($uniqueTagIds as $tagId) {
                                $query->orWhereRaw('FIND_IN_SET(?, tag_id)', [$tagId]);
                            }
                        });

                    // Exclude the news that are already in the original category/subcategory results
                    if (!empty($originalNewsIds)) {
                        $tagRelatedQuery->whereNotIn('id', $originalNewsIds);
                    }

                    // Use union to combine both queries
                    // First, apply ordering to each individual query before the union
                    if (isset($request->latitude) && isset($request->longitude)) {
                        $originalQuery->orderByRaw('CASE WHEN distance IS NULL THEN 1 ELSE 0 END, distance DESC');
                        $tagRelatedQuery->orderByRaw('CASE WHEN distance IS NULL THEN 1 ELSE 0 END, distance DESC');
                    } else {
                        $originalQuery->orderBy('id', 'DESC');
                        $tagRelatedQuery->orderBy('id', 'DESC');
                    }

                    // Create the union without final ordering
                    $unionQuery = $originalQuery->union($tagRelatedQuery);

                    // Use raw DB query to wrap the union result in a subquery
                    $news = DB::table(DB::raw("({$unionQuery->toSql()}) as news_union"))
                        ->mergeBindings($unionQuery->getQuery())
                        ->select('*');

                    // Now we can safely order the combined results
                    if (isset($request->latitude) && isset($request->longitude)) {
                        $news->orderByRaw('CASE WHEN distance IS NULL THEN 1 ELSE 0 END, distance DESC');
                    } else {
                        // $news->orderBy('id', 'DESC');
                        $news->reorder('id', 'DESC');
                    }

                    // No more tbl_news references needed after this point
                    $total = $news->count();
                    if ($total) {

                        $res = $news->skip($offset)->take($limit)->get();

                        // Calculate and set the 'distance' for each news item
                        $res->each(function ($item) {
                            //get other data (total_like, total_views etc..)
                            $item = $this->getNewsData($item, $item->id);


                            if (!empty($item->image) && strpos($item->image, 'news/') === false) {
                                $item->image = 'news/' . $item->image;
                            }
                            $item->image = $item->image && Storage::disk('public')->exists($item->image) ? url(Storage::url($item->image)) : '';

                            if ($item->content_type == 'video_upload') {
                                if (!empty($item->content_value) && strpos($item->content_value, 'news_video/') === false) {
                                    $content_value = 'news_video/' . $item->content_value;
                                } else {
                                    $content_value = $item->content_value;
                                }
                                $item->content_value = url(Storage::url('/' . $content_value));
                            }
                        });

                        return ResponseService::successResponse(__('Data fetched successfully'), [
                            'total' => $total,
                            'data' => $res,
                        ]);
                    } else {
                        return ResponseService::errorResponse(__('No Data Found'), null, config('constants.RESPONSE_CODE.NOT_FOUND'), null);
                    }
                }
            }

            // This code only runs if no union query was created (no tag-related news)
            $news->select('tbl_news.*');
            if (isset($request->latitude) && isset($request->longitude)) {

                // dd($request->latitude, $request->longitude, gettype($this->nearest_location_measure));
                $news->join('tbl_location', 'tbl_news.location_id', '=', 'tbl_location.id', 'left')
                    ->selectRaw('SQRT(POW(111.2 * (tbl_location.latitude - ?), 2) + POW(111.2 * (? - tbl_location.longitude) * COS(RADIANS(tbl_location.latitude) / 57.3), 2)) AS distance', [$latitude, $longitude])
                    ->where(function ($q1) {
                        $q1->having(DB::raw('distance <' . (int) $this->nearest_location_measure . ' OR tbl_news.location_id=. 0'));
                    })
                    ->orderByRaw('CASE WHEN distance IS NULL THEN 1 ELSE 0 END, distance DESC');
            } else {
                // $news->orderBy('tbl_news.id', 'DESC');
                $news->reorder('tbl_news.id', 'DESC');
            }
            // dd("out condition");
            $total = $news->clone()->count();
            // dd($total);
            if ($total) {
                $res = $news->clone()->skip($offset)->take($limit)->get();

                // Calculate and set the 'distance' for each news item
                $res->each(function ($item) {
                    //get other data (total_like, total_views etc..)
                    $item = $this->getNewsData($item, $item->id);

                    if ($item->content_type == 'video_upload') {
                        if (!empty($item->content_value) && strpos($item->content_value, 'news_video/') === false) {
                            $content_value = 'news_video/' . $item->content_value;
                        } else {
                            $content_value = $item->content_value;
                        }
                        $item->content_value = url(Storage::url('/' . $content_value));
                    }
                });

                return ResponseService::successResponse(__('Data fetched successfully'), [
                    'total' => $total,
                    'data' => $res,
                ]);
            } else {
                return ResponseService::errorResponse(__('No Data Found'), null, config('constants.RESPONSE_CODE.NOT_FOUND'), null);
            }
        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, 'API Controller -> setBreakingNewsView');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function getFeaturedSections(Request $request)
    {
        try {

            $user_id = Auth::check() ? Auth::user()->id : 0;
            $languageId = $this->resolveLanguageId($request);
            $news_type = $request->news_type ?? '';
            $style_web = $request->style_web ?? '';
            $latitude = $request->latitude ?? 0;
            $longitude = $request->longitude ?? 0;

            $res = FeaturedSections::where('language_id', $languageId)->where('status', 1);
            if ($request->section_id) {
                $res = $res->where('id', $request->section_id);
            } elseif ($request->slug) {
                $res = $res->where('slug', $request->slug);
            } else if (!empty($news_type) && !empty($style_web)) {
                $res = $res->where('news_type', $news_type)->where('style_web', $style_web);
            }
            $total = $res->clone()->count('id');
            if ($total) {
                $data = $res->clone()->orderBy('row_order', 'ASC');
                $data = $data->offset($request->section_offset ?? 0)->take($request->section_limit ?? 10);
                $data = $data->get();
                foreach ($data as $key => $row) {

                    // $row->news_type = $row->news_type == 'author_news' ? 'news' : $row->news_type;

                    $results = [];
                    if ($row->news_type == 'news' || $row->news_type == 'videos' || $row->news_type == 'author_news') {
                        if ($row->filter_type == 'most_commented') {
                            $results = DB::table('tbl_news')
                                ->select('tbl_news.*', 'tbl_category.category_name', DB::raw('IFNULL(tbl_subcategory.subcategory_name, "") AS subcategory_name'), 'tbl_comment.newscount', DB::raw('SQRT(POW(111.2 * (tbl_location.latitude - ' . $latitude . '), 2) + POW(111.2 * (' . $longitude . ' - tbl_location.longitude) * COS(tbl_location.latitude / 57.3), 2)) AS distance'))
                                ->where('tbl_news.is_draft', 0)
                                ->join('tbl_category', 'tbl_news.category_id', '=', 'tbl_category.id')
                                ->leftJoin('tbl_subcategory', 'tbl_news.subcategory_id', '=', 'tbl_subcategory.id')
                                ->leftJoin('tbl_location', 'tbl_news.location_id', '=', 'tbl_location.id')
                                // ->where('tbl_news.published_date', '<=', $this->toDate)
                                ->where(function ($q) {
                                    $q->where(function ($subq) {
                                        $subq->whereNotNull('tbl_news.published_date')
                                            ->where('tbl_news.published_date', '<=', $this->toDate);
                                    })->orWhere(function ($subq) {
                                        $subq->whereNull('tbl_news.published_date')
                                            ->whereDate('tbl_news.created_at', '<=', $this->toDate);
                                    });
                                })
                                ->where('tbl_news.status', 1)->where('tbl_news.language_id', $languageId)
                                ->where(function ($q) {
                                    $q->where('tbl_news.show_till', '>=', $this->toDate)->orWhereRaw("CAST(tbl_news.show_till AS CHAR(20)) = '0000-00-00'");
                                })
                                ->join(DB::raw('(SELECT news_id, COUNT(*) AS newscount FROM tbl_comment GROUP BY news_id) AS tbl_comment'), function ($join) {
                                    $join->on('tbl_news.id', '=', 'tbl_comment.news_id');
                                });
                            if ($row->category_ids != null && $row->subcategory_ids != null) {
                                $results->where(function ($q1) use ($row) {
                                    $q1->whereIn('tbl_news.category_id', explode(',', $row->category_ids))->orWhereIn('tbl_news.subcategory_id', explode(',', $row->subcategory_ids));
                                });
                            } elseif ($row->category_ids != null && $row->subcategory_ids == null) {
                                $results->whereIn('tbl_news.category_id', explode(',', $row->category_ids));
                            } elseif ($row->category_ids == null && $row->subcategory_ids != null) {
                                $results->whereIn('tbl_news.subcategory_id', explode(',', $row->subcategory_ids));
                            }
                            if ($row->news_type == 'videos' && $row->videos_type == 'news') {
                                $results->where('tbl_news.description', '!=', '')->whereIn('tbl_news.content_type', ['video_upload', 'video_youtube', 'video_other']);
                            }
                            $nearest_location_measure = $this->nearest_location_measure;
                            if (isset($latitude) && isset($longitude) && $latitude != null && $longitude != null) {
                                $results->havingRaw("distance < $nearest_location_measure OR tbl_news.location_id = 0");
                            }
                            $orderby = 'tbl_comment.newscount';
                            $result_count = $results->count();
                            $offset = $request->offset ?? 0;
                            $limit = $request->limit ?? 10;
                            $results = $results->skip($offset)->take($limit);
                            $results = $results->orderBy($orderby, 'DESC')->get();
                            // $query = str_replace(array('?'), array('\'%s\''), $results->toSql());
                            // return vsprintf($query, $results->getBindings());
                        } elseif ($row->filter_type == 'recently_added') {
                            $results = DB::table('tbl_news')
                                ->select('tbl_news.*', 'tbl_category.category_name', DB::raw('IFNULL(tbl_subcategory.subcategory_name, "") AS subcategory_name'), DB::raw('SQRT(POW(111.2 * (tbl_location.latitude - ' . $latitude . '), 2) + POW(111.2 * (' . $longitude . ' - tbl_location.longitude) * COS(tbl_location.latitude / 57.3), 2)) AS distance'))
                                ->where('tbl_news.is_draft', 0)
                                ->leftJoin('tbl_category', 'tbl_news.category_id', '=', 'tbl_category.id')
                                ->leftJoin('tbl_subcategory', 'tbl_news.subcategory_id', '=', 'tbl_subcategory.id')
                                ->leftJoin('tbl_location', 'tbl_news.location_id', '=', 'tbl_location.id')
                                // ->where('tbl_news.published_date', '<=', $this->toDate)
                                ->where(function ($q) {
                                    $q->where(function ($subq) {
                                        $subq->whereNotNull('tbl_news.published_date')
                                            ->where('tbl_news.published_date', '<=', $this->toDate);
                                    })->orWhere(function ($subq) {
                                        $subq->whereNull('tbl_news.published_date')
                                            ->whereDate('tbl_news.created_at', '<=', $this->toDate);
                                    });
                                })
                                ->where('tbl_news.status', 1)->where('tbl_news.language_id', $languageId)
                                ->where(function ($q) {
                                    $q->where('tbl_news.show_till', '>=', $this->toDate)->orWhereRaw("CAST(tbl_news.show_till AS CHAR(20)) = '0000-00-00'");
                                });
                            if ($row->category_ids != null && $row->subcategory_ids != null) {
                                $results->where(function ($q1) use ($row) {
                                    $q1->whereIn('tbl_news.category_id', explode(',', $row->category_ids))->WhereIn('tbl_news.subcategory_id', explode(',', $row->subcategory_ids));
                                });
                            } elseif ($row->category_ids != null && $row->subcategory_ids == null) {
                                $results->where(function ($q1) use ($row) {
                                    $q1->whereIn('tbl_news.category_id', explode(',', $row->category_ids));
                                });
                            } elseif ($row->category_ids == null && $row->subcategory_ids != null) {
                                $results->where(function ($q1) use ($row) {
                                    $q1->whereIn('tbl_news.subcategory_id', explode(',', $row->subcategory_ids));
                                });
                            }
                            $nearest_location_measure = $this->nearest_location_measure;
                            // Append condition based on latitude and longitude
                            if (isset($latitude) && isset($longitude) && $latitude != null && $longitude != null) {
                                $results->havingRaw("distance < $nearest_location_measure OR tbl_news.location_id = 0");
                            }
                            if ($row->news_type == 'news') {
                                $results->where('tbl_news.description', '!=', '');
                                $result_count = $results->count();
                                $offset = $request->offset ?? 0;
                                $limit = $request->limit ?? 10;
                                $results = $results->skip($offset)->take($limit);
                                $results = $results->orderBy('tbl_news.id', 'DESC')->get();
                            } elseif ($row->news_type == 'videos') {
                                if ($row->videos_type == 'news') {
                                    $results->whereIn('tbl_news.content_type', ['video_upload', 'video_youtube', 'video_other']);
                                    $result_count = $results->count();
                                    $offset = $request->offset ?? 0;
                                    $limit = $request->limit ?? 10;
                                    $results = $results->skip($offset)->take($limit);
                                    $results = $results->orderBy('tbl_news.id', 'DESC')->get();
                                } elseif ($row->videos_type == 'breaking_news') {
                                    //1.5 recently_added breaking_news video
                                    $breaking_news = DB::table('tbl_breaking_news')->select('tbl_breaking_news.*')->where('tbl_breaking_news.language_id', $language_id)->whereIn('tbl_breaking_news.content_type', ['video_upload', 'video_youtube', 'video_other']);
                                    $result_count = $breaking_news->clone()->count();
                                    $results = $breaking_news->clone();
                                    $offset = $request->offset ?? 0;
                                    $limit = $request->limit ?? 10;
                                    $results = $results->skip($offset)->take($limit);
                                    $results = $results->orderBy('tbl_breaking_news.id', 'DESC')->get();
                                }
                            }
                        } elseif ($row->filter_type == 'most_viewed') {
                            $results = DB::table('tbl_news')
                                ->select('tbl_news.*', 'tbl_category.category_name', DB::raw('IFNULL(tbl_subcategory.subcategory_name, "") AS subcategory_name'), 'tbl_news_view.viewcount', DB::raw('SQRT(POW(111.2 * (tbl_location.latitude - ' . $latitude . '), 2) + POW(111.2 * (' . $longitude . ' - tbl_location.longitude) * COS(tbl_location.latitude / 57.3), 2)) AS distance'))
                                ->where('tbl_news.is_draft', 0)
                                ->join('tbl_category', 'tbl_news.category_id', '=', 'tbl_category.id')
                                ->leftJoin('tbl_subcategory', 'tbl_news.subcategory_id', '=', 'tbl_subcategory.id')
                                ->leftJoin('tbl_location', 'tbl_news.location_id', '=', 'tbl_location.id')
                                ->join(DB::raw('(SELECT news_id, COUNT(*) AS viewcount FROM tbl_news_view GROUP BY news_id) AS tbl_news_view'), function ($join) {
                                    $join->on('tbl_news.id', '=', 'tbl_news_view.news_id');
                                })
                                // ->where('tbl_news.published_date', '<=', $this->toDate)
                                ->where(function ($q) {
                                    $q->where(function ($subq) {
                                        $subq->whereNotNull('tbl_news.published_date')
                                            ->where('tbl_news.published_date', '<=', $this->toDate);
                                    })->orWhere(function ($subq) {
                                        $subq->whereNull('tbl_news.published_date')
                                            ->whereDate('tbl_news.created_at', '<=', $this->toDate);
                                    });
                                })
                                ->where('tbl_news.status', 1)->where('tbl_news.language_id', $languageId)
                                ->where(function ($q) {
                                    $q->where('tbl_news.show_till', '>=', $this->toDate)->orWhereRaw("CAST(tbl_news.show_till AS CHAR(20)) = '0000-00-00'");
                                });
                            if ($row->category_ids != null && $row->subcategory_ids != null) {
                                $results->where(function ($q1) use ($row) {
                                    $q1->whereIn('tbl_news.category_id', explode(',', $row->category_ids))->orWhereIn('tbl_news.subcategory_id', explode(',', $row->subcategory_ids));
                                });
                            } elseif ($row->category_ids != null && $row->subcategory_ids == null) {
                                $results->where(function ($q1) use ($row) {
                                    $q1->whereIn('tbl_news.category_id', explode(',', $row->category_ids));
                                });
                            } elseif ($row->category_ids == null && $row->subcategory_ids != null) {
                                $results->where(function ($q1) use ($row) {
                                    $q1->whereIn('tbl_news.subcategory_id', explode(',', $row->subcategory_ids));
                                });
                            }
                            $nearest_location_measure = $this->nearest_location_measure;
                            if (isset($latitude) && isset($longitude) && $latitude != null && $longitude != null) {
                                $results->havingRaw("distance < $nearest_location_measure OR tbl_news.location_id = 0");
                            }
                            if ($row->news_type == 'news') {
                                $results->where('tbl_news.description', '!=', '');
                                $result_count = $results->count();
                                $offset = $request->offset ?? 0;
                                $limit = $request->limit ?? 10;
                                $results = $results->skip($offset)->take($limit);
                                $results = $results->orderBy('tbl_news_view.viewcount', 'DESC')->get();
                            } elseif ($row->news_type == 'videos') {
                                if ($row->videos_type == 'news') {
                                    $results->whereIn('tbl_news.content_type', ['video_upload', 'video_youtube', 'video_other']);
                                    $result_count = $results->count();
                                    $offset = $request->offset ?? 0;
                                    $limit = $request->limit ?? 10;
                                    $results = $results->skip($offset)->take($limit);
                                    $results = $results->orderBy('tbl_news_view.viewcount', 'DESC')->get();
                                } elseif ($row->videos_type == 'breaking_news') {
                                    $breaking_news = DB::table('tbl_breaking_news')
                                        ->select('tbl_breaking_news.*', 'tbl_breaking_news_view.viewcount')
                                        ->where('tbl_breaking_news.language_id', $languageId)
                                        ->whereIn('tbl_breaking_news.content_type', ['video_upload', 'video_youtube', 'video_other'])
                                        ->join(DB::raw('(SELECT breaking_news_id, COUNT(*) AS viewcount FROM tbl_breaking_news_view GROUP BY breaking_news_id) AS tbl_breaking_news_view'), function ($join) {
                                            $join->on('tbl_breaking_news.id', '=', 'tbl_breaking_news_view.breaking_news_id');
                                        });
                                    $result_count = $breaking_news->clone()->count();
                                    $results = $breaking_news->clone();
                                    $offset = $request->offset ?? 0;
                                    $limit = $request->limit ?? 10;
                                    $results = $results->skip($offset)->take($limit);
                                    $results = $results->orderBy('tbl_breaking_news_view.viewcount', 'DESC')->get();
                                }
                            }
                        } elseif ($row->filter_type == 'most_favorite') {
                            //1.9 most_favorite news, video
                            $results = DB::table('tbl_news')
                                ->select('tbl_news.*', 'tbl_category.category_name', DB::raw('IFNULL(tbl_subcategory.subcategory_name, "") AS subcategory_name'), 'tbl_bookmark.newscount', DB::raw('SQRT(POW(111.2 * (tbl_location.latitude - ' . $latitude . '), 2) + POW(111.2 * (' . $longitude . ' - tbl_location.longitude) * COS(tbl_location.latitude / 57.3), 2)) AS distance'))
                                ->where('tbl_news.is_draft', 0)
                                ->join('tbl_category', 'tbl_news.category_id', '=', 'tbl_category.id')
                                ->leftJoin('tbl_subcategory', 'tbl_news.subcategory_id', '=', 'tbl_subcategory.id')
                                ->leftJoin('tbl_location', 'tbl_news.location_id', '=', 'tbl_location.id')
                                // ->where('tbl_news.published_date', '<=', $this->toDate)
                                ->where(function ($q) {
                                    $q->where(function ($subq) {
                                        $subq->whereNotNull('tbl_news.published_date')
                                            ->where('tbl_news.published_date', '<=', $this->toDate);
                                    })->orWhere(function ($subq) {
                                        $subq->whereNull('tbl_news.published_date')
                                            ->whereDate('tbl_news.created_at', '<=', $this->toDate);
                                    });
                                })
                                ->where('tbl_news.status', 1)->where('tbl_news.language_id', $languageId)->where('tbl_news.description', '!=', '')
                                ->where(function ($q) {
                                    $q->where('tbl_news.show_till', '>=', $this->toDate)->orWhereRaw("CAST(tbl_news.show_till AS CHAR(20)) = '0000-00-00'");
                                })
                                ->join(DB::raw('(SELECT news_id, COUNT(*) AS newscount FROM tbl_bookmark GROUP BY news_id) AS tbl_bookmark'), function ($join) {
                                    $join->on('tbl_news.id', '=', 'tbl_bookmark.news_id');
                                });
                            if ($row->news_type == 'videos') {
                                $results->whereIn('tbl_news.content_type', ['video_upload', 'video_youtube', 'video_other']);
                            }
                            if ($row->category_ids != null && $row->subcategory_ids != null) {
                                $results->where(function ($q1) use ($row) {
                                    $q1->whereIn('tbl_news.category_id', explode(',', $row->category_ids))->orWhereIn('tbl_news.subcategory_id', explode(',', $row->subcategory_ids));
                                });
                            } elseif ($row->category_ids != null && $row->subcategory_ids == null) {
                                $results->where(function ($q1) use ($row) {
                                    $q1->whereIn('tbl_news.category_id', explode(',', $row->category_ids));
                                });
                            } elseif ($row->category_ids == null && $row->subcategory_ids != null) {
                                $results->where(function ($q1) use ($row) {
                                    $q1->whereIn('tbl_news.subcategory_id', explode(',', $row->subcategory_ids));
                                });
                            } elseif ($row->category_ids == null && $row == null) {
                            }
                            $nearest_location_measure = $this->nearest_location_measure;
                            // Append condition based on latitude and longitude
                            if (isset($latitude) && isset($longitude) && $latitude != null && $longitude != null) {
                                $results->havingRaw("distance < $nearest_location_measure OR tbl_news.location_id = 0");
                            }
                            $result_count = $results->count();
                            $offset = $request->offset ?? 0;
                            $limit = $request->limit ?? 10;
                            $results = $results->skip($offset)->take($limit);
                            $results = $results->orderBy('tbl_bookmark.newscount', 'DESC')->get();
                        } elseif ($row->filter_type == 'most_like') {
                            //1.9 most_favorite like, video
                            $results = DB::table('tbl_news')
                                ->select('tbl_news.*', 'tbl_category.category_name', DB::raw('IFNULL(tbl_subcategory.subcategory_name, "") AS subcategory_name'), 'tbl_news_like.likecount', DB::raw('SQRT(POW(111.2 * (tbl_location.latitude - ' . $latitude . '), 2) + POW(111.2 * (' . $longitude . ' - tbl_location.longitude) * COS(tbl_location.latitude / 57.3), 2)) AS distance'))
                                ->where('tbl_news.is_draft', 0)
                                ->join('tbl_category', 'tbl_news.category_id', '=', 'tbl_category.id')
                                ->leftJoin('tbl_subcategory', 'tbl_news.subcategory_id', '=', 'tbl_subcategory.id')
                                ->leftJoin('tbl_location', 'tbl_news.location_id', '=', 'tbl_location.id')
                                // ->where('tbl_news.published_date', '<=', $this->toDate)
                                ->where(function ($q) {
                                    $q->where(function ($subq) {
                                        $subq->whereNotNull('tbl_news.published_date')
                                            ->where('tbl_news.published_date', '<=', $this->toDate);
                                    })->orWhere(function ($subq) {
                                        $subq->whereNull('tbl_news.published_date')
                                            ->whereDate('tbl_news.created_at', '<=', $this->toDate);
                                    });
                                })
                                ->where('tbl_news.status', 1)->where('tbl_news.language_id', $languageId)->where('tbl_news.description', '!=', '')
                                ->where(function ($q) {
                                    $q->where('tbl_news.show_till', '>=', $this->toDate)->orWhereRaw("CAST(tbl_news.show_till AS CHAR(20)) = '0000-00-00'");
                                })
                                ->join(DB::raw('(SELECT news_id, COUNT(*) AS likecount FROM tbl_news_like WHERE status="1" GROUP BY news_id) AS tbl_news_like'), function ($join) {
                                    $join->on('tbl_news.id', '=', 'tbl_news_like.news_id');
                                });
                            if ($row->category_ids != null && $row->subcategory_ids != null) {
                                $results->where(function ($q1) use ($row) {
                                    $q1->whereIn('tbl_news.category_id', explode(',', $row->category_ids))->orWhereIn('tbl_news.subcategory_id', explode(',', $row->subcategory_ids));
                                });
                            } elseif ($row->category_ids != null && $row->subcategory_ids == null) {
                                $results->where(function ($q1) use ($row) {
                                    $q1->whereIn('tbl_news.category_id', explode(',', $row->category_ids));
                                });
                            } elseif ($row->category_ids == null && $row->subcategory_ids != null) {
                                $results->where(function ($q1) use ($row) {
                                    $q1->whereIn('tbl_news.subcategory_id', explode(',', $row->subcategory_ids));
                                });
                            }
                            if ($row->news_type == 'videos') {
                                $results->whereIn('tbl_news.content_type', ['video_upload', 'video_youtube', 'video_other']);
                            }
                            $nearest_location_measure = $this->nearest_location_measure;
                            // Append condition based on latitude and longitude
                            if (isset($latitude) && isset($longitude) && $latitude != null && $longitude != null) {
                                $results->havingRaw("distance < $nearest_location_measure OR tbl_news.location_id = 0");
                            }
                            $result_count = $results->count();
                            $offset = $request->offset ?? 0;
                            $limit = $request->limit ?? 10;
                            $results = $results->skip($offset)->take($limit);
                            $results = $results->orderBy('tbl_news_like.likecount', 'DESC')->get();
                        } elseif ($row->filter_type == 'custom') {

                            $results = DB::table('tbl_news')
                                ->select('tbl_news.*', 'tbl_category.category_name', DB::raw('IFNULL(tbl_subcategory.subcategory_name, "") AS subcategory_name'), DB::raw('SQRT(POW(111.2 * (tbl_location.latitude - ' . $latitude . '), 2) + POW(111.2 * (' . $longitude . ' - tbl_location.longitude) * COS(tbl_location.latitude / 57.3), 2)) AS distance'))
                                ->where('tbl_news.is_draft', 0)
                                ->leftJoin('tbl_category', 'tbl_news.category_id', '=', 'tbl_category.id')
                                ->leftJoin('tbl_subcategory', 'tbl_news.subcategory_id', '=', 'tbl_subcategory.id')
                                ->leftJoin('tbl_location', 'tbl_news.location_id', '=', 'tbl_location.id')
                                // ->where('tbl_news.published_date', '<=', $this->toDate)
                                ->where(function ($q) {
                                    $q->where(function ($subq) {
                                        $subq->whereNotNull('tbl_news.published_date')
                                            ->where('tbl_news.published_date', '<=', $this->toDate);
                                    })->orWhere(function ($subq) {
                                        $subq->whereNull('tbl_news.published_date')
                                            ->whereDate('tbl_news.created_at', '<=', $this->toDate);
                                    });
                                })
                                ->where('tbl_news.status', 1)->whereIn('tbl_news.id', explode(',', $row->news_ids))->where('tbl_news.language_id', $languageId)
                                ->where(function ($q) {
                                    $q->where('tbl_news.show_till', '>=', $this->toDate)->orWhereRaw("CAST(tbl_news.show_till AS CHAR(20)) = '0000-00-00'");
                                });
                            $nearest_location_measure = $this->nearest_location_measure;
                            // Append condition based on latitude and longitude
                            if (isset($latitude) && isset($longitude) && $latitude != null && $longitude != null) {
                                $results->havingRaw("distance < $nearest_location_measure OR tbl_news.location_id = 0");
                            }
                            //1.10 custom (based on selected category, subcategory)
                            if ($row->news_type == 'news' || $row->news_type == 'author_news') {
                                $result_count = $results->count();
                                $offset = $request->offset ?? 0;
                                $limit = $request->limit ?? 10;
                                $results = $results->skip($offset)->take($limit);
                                $results = $results->orderBy('tbl_news.id', 'DESC')->get();
                            } elseif ($row->news_type == 'videos') {
                                if ($row->videos_type == 'news') {
                                    $results->whereIn('tbl_news.content_type', ['video_upload', 'video_youtube', 'video_other']);
                                    $result_count = $results->count();
                                    $offset = $request->offset ?? 0;
                                    $limit = $request->limit ?? 10;
                                    $results = $results->skip($offset)->take($limit);
                                    $results = $results->orderBy('tbl_news.id', 'DESC')->get();
                                } elseif ($row->videos_type == 'breaking_news') {
                                    //1.10.1 custom breaking_news video
                                    $breaking_news = DB::table('tbl_breaking_news')->select('tbl_breaking_news.*')
                                        ->whereIn('tbl_breaking_news.content_type', ['video_upload', 'video_youtube', 'video_other'])
                                        ->whereIn('tbl_breaking_news.id', explode(',', $row->news_ids))->where('tbl_breaking_news.language_id', $languageId);
                                    $result_count = $breaking_news->clone()->count();
                                    $results = $breaking_news->clone();
                                    $offset = $request->offset ?? 0;
                                    $limit = $request->limit ?? 10;
                                    $results = $results->skip($offset)->take($limit);
                                    $results = $results->orderBy('tbl_breaking_news.id', 'DESC')->get();
                                }
                            }
                        }
                    } elseif ($row->news_type == 'breaking_news') {
                        //2. Breaking News
                        $breakingNewsQuery = DB::table('tbl_breaking_news')->where('language_id', $languageId);
                        $result_count = 0;
                        $results = collect();

                        if ($row->filter_type == 'recently_added') {
                            $breaking_news = DB::table('tbl_breaking_news')->where('language_id', $languageId);
                            $result_count = $breaking_news->clone()->count();
                            $results = $breaking_news->clone();
                            $offset = $request->offset ?? 0;
                            $limit = $request->limit ?? 10;
                            $results = $results->skip($offset)->take($limit);
                            $results = $results->orderBy('tbl_breaking_news.id', 'DESC')->get();
                        } elseif ($row->filter_type == 'most_viewed') {
                            //2.2 Breaking News most_viewed
                            $results = DB::table('tbl_breaking_news')->select('tbl_breaking_news.*')
                                ->join(DB::raw('(SELECT breaking_news_id, COUNT(*) AS viewcount FROM tbl_breaking_news_view GROUP BY breaking_news_id) AS tbl_breaking_news_view'), function ($join) {
                                    $join->on('tbl_breaking_news_view.breaking_news_id', '=', 'tbl_breaking_news.id');
                                })
                                ->where('tbl_breaking_news.language_id', $languageId);
                            $result_count = $results->count();
                            $offset = $request->offset ?? 0;
                            $limit = $request->limit ?? 10;
                            $results = $results->skip($offset)->take($limit);
                            $results = $results->orderBy('tbl_breaking_news_view.viewcount', 'DESC')->get();
                        } elseif ($row->filter_type == 'custom') {
                            $results = DB::table('tbl_breaking_news')
                                ->whereIn('id', explode(',', $row->news_ids))
                                ->where('language_id', $languageId);
                            $result_count = $results->count();
                            $offset = $request->offset ?? 0;
                            $limit = $request->limit ?? 10;
                            $results = $results->skip($offset)->take($limit);
                            $results = $results->orderBy('id', 'DESC')->get();
                        }
                    } elseif ($row->is_based_on_user_choice == '1') {
                        // based_on_user's_choice_section code ** different from above all section //
                        if (Auth::check()) {
                            $user_category = UserCategory::select('id', 'category_id')
                                ->where('user_id', Auth::user()->id)
                                ->first();
                        } else {
                            $user_category = null;
                        }
                        if ($user_category != null) {
                            $results = DB::table('tbl_news')
                                ->select('tbl_news.*', 'tbl_category.category_name', 'tbl_subcategory.subcategory_name', DB::raw('SQRT(POW(111.2 * (tbl_location.latitude - ' . $latitude . '), 2) + POW(111.2 * (' . $longitude . ' - tbl_location.longitude) * COS(tbl_location.latitude / 57.3), 2)) AS distance'))
                                ->where('tbl_news.is_draft', 0)
                                ->join('tbl_category', 'tbl_news.category_id', '=', 'tbl_category.id')
                                ->leftJoin('tbl_subcategory', 'tbl_news.subcategory_id', '=', 'tbl_subcategory.id')
                                ->leftJoin('tbl_location', 'tbl_news.location_id', '=', 'tbl_location.id')
                                // ->where('tbl_news.published_date', '<=', $this->toDate)
                                ->where(function ($q) {
                                    $q->where(function ($subq) {
                                        $subq->whereNotNull('tbl_news.published_date')
                                            ->where('tbl_news.published_date', '<=', $this->toDate);
                                    })->orWhere(function ($subq) {
                                        $subq->whereNull('tbl_news.published_date')
                                            ->whereDate('tbl_news.created_at', '<=', $this->toDate);
                                    });
                                })
                                ->where('tbl_news.status', 1)->where('tbl_news.language_id', $languageId)
                                ->whereIn('tbl_news.category_id', explode(',', $user_category->category_id))
                                ->where(function ($q) {
                                    $q->where('tbl_news.show_till', '>=', $this->toDate)->orWhereRaw("CAST(tbl_news.show_till AS CHAR(20)) = '0000-00-00'");
                                });
                            $nearest_location_measure = $this->nearest_location_measure;
                            // Append condition based on latitude and longitude
                            if (isset($latitude) && isset($longitude) && $latitude != null && $longitude != null) {
                                $results->havingRaw("distance < $nearest_location_measure OR tbl_news.location_id = 0");
                            }
                            $result_count = $results->count();
                            $offset = $request->offset ?? 0;
                            $limit = $request->limit ?? 10;
                            $results = $results->skip($offset)->take($limit);
                            $results = $results->orderBy('tbl_news.id', 'DESC')->get();
                        } else {
                            $result_count = 0;
                            $results = collect();
                        }
                    } elseif ($row->news_type == 'rss_feeds_news') {
                        $results = collect();
                        $allRssItems = [];

                        // Get RSS feed configurations linked to this featured section
                        $rssFeedConfigs = FeaturedSectionRssFeed::where('featured_section_id', $row->id)
                            ->with([
                                'rss_feed' => function ($query) use ($languageId) {
                                    $query->where('status', 1)->where('language_id', $languageId)
                                        ->with(['category:id,category_name', 'sub_category:id,subcategory_name']);
                                }
                            ])
                            ->get();

                        // Fetch and parse RSS feed items from each RSS feed URL with caching
                        foreach ($rssFeedConfigs as $rssFeedConfig) {
                            if (!$rssFeedConfig->rss_feed || !$rssFeedConfig->rss_feed->feed_url) {
                                continue;
                            }

                            $rss = $rssFeedConfig->rss_feed;
                            $cacheKey = "rss_feed_{$rss->id}";

                            // Check cache first
                            $cachedItems = Cache::store(config('cache.default'))->get($cacheKey);

                            if ($cachedItems !== null) {
                                // Use cached data - convert arrays to objects to match existing structure
                                foreach ($cachedItems as $item) {
                                    $allRssItems[] = (object) $item;
                                }
                            } else {
                                // If not cached, use the job to fetch and cache
                                try {
                                    $job = new FetchRssFeedJob($rss);
                                    $feedItems = $job->handle();

                                    // // Shuffle items to randomize order
                                    // $feedItems = $feedItems->shuffle();

                                    // Convert collection items to objects to match existing structure
                                    foreach ($feedItems as $item) {
                                        $allRssItems[] = (object) $item;
                                    }

                                    // Dispatch job for background cache refresh
                                    // FetchRssFeedJob::dispatch($rss);
                                } catch (\Exception $e) {
                                    Log::error('RSS feed processing error', [
                                        'url' => $rss->feed_url ?? 'unknown',
                                        'rss_id' => $rss->id,
                                        'error' => $e->getMessage(),
                                    ]);
                                    continue;
                                }
                            }
                        }

                        // Sort by publication date (newest first)
                        usort($allRssItems, function ($a, $b) {
                            $dateA = strtotime($a->published_date ?? $a->date ?? '');
                            $dateB = strtotime($b->published_date ?? $b->date ?? '');
                            return $dateB - $dateA;
                        });

                        //// Shuffle all items to randomize order
                        // shuffle($allRssItems);
                        // Apply pagination
                        $offset = $request->input('offset', 0);
                        $limit = $request->input('limit', 10);
                        $result_count = count($allRssItems);
                        $allRssItems = array_slice($allRssItems, $offset, $limit);
                        $results = collect($allRssItems);
                    }elseif ($row->news_type == 'author_news') {
                    
                        $row->news_type = $row->news_type == 'author_news' ? 'news' : $row->news_type;
                        $query = DB::table('tbl_news')
                                ->select(
                                    'tbl_news.*',
                                    'tbl_category.category_name',
                                    DB::raw('IFNULL(tbl_subcategory.subcategory_name, "") AS subcategory_name'),
                                )
                                ->where('tbl_news.is_draft', 0)
                                ->where('tbl_news.status', 1)
                                ->where('tbl_news.language_id', $languageId)
                                ->leftJoin('tbl_subcategory', 'tbl_news.subcategory_id', '=', 'tbl_subcategory.id')
                                ->leftJoin('tbl_location', 'tbl_news.location_id', '=', 'tbl_location.id');

                        $query->leftJoin('tbl_category', 'tbl_news.category_id', '=', 'tbl_category.id');
        

                        if ($row->user_ids) {
                            $query->whereIn('tbl_news.user_id', explode(',', $row->user_ids));
                        }
                         $result_count = $query->count();
                        $results = $query->skip($request->offset)->take($request->limit)->orderBy('tbl_news.id', 'desc')->get();
                    }
                    if ($results) {
                        // dd($results->toArray());
                        foreach ($results as $row2) {
                            if ($row->news_type == 'news' || $row->is_based_on_user_choice == '1') {
                                //get other data (total_like, total_views etc..)
                                $row2 = $this->getNewsData($row2, $row2->id);

                                if ($row2->content_type == 'video_upload') {
                                    if (!empty($row2->content_value) && strpos($row2->content_value, 'news_video/') === false) {
                                        $content_value = 'news_video/' . $row2->content_value;
                                    } else {
                                        $content_value = $row2->content_value;
                                    }
                                    $row2->content_value = url(Storage::url($content_value));
                                }
                                if (!empty($row2->image) && strpos($row2->image, 'news/') === false) {
                                    $image = 'news/' . $row2->image;
                                } else {
                                    $image = $row2->image;
                                }
                                $row2->image = url(Storage::url($image));
                                $img = [];
                                $images = News_image::with('news')->where('news_id', $row2->id)->get();
                                $imageArray = $images->map(function ($image) {
                                    return [
                                        'other_image' => url(Storage::url($image->getOtherImagePathAttribute())),
                                    ];
                                })->toArray();

                                $row2->images = $imageArray;
                            } elseif ($row->news_type == 'breaking_news') {
                                if (!empty($row2->image) && strpos($row2->image, 'breaking_news/') === false) {
                                    $image = 'breaking_news/' . $row2->image;
                                } else {
                                    $image = $row2->image;
                                }
                                $row2->image = url(Storage::url($image));
                                if ($row2->content_type == 'video_upload') {
                                    if (!empty($row2->content_value) && strpos($row2->content_value, 'breaking_news_video/') === false) {
                                        $content_value = 'breaking_news_video/' . $row2->content_value;
                                    } else {
                                        $content_value = $row2->content_value;
                                    }
                                    $row2->content_value = url(Storage::url($content_value));
                                }
                                $row2->total_views = BreakingNewsView::where('breaking_news_id', $row2->id)->count('id');
                            } elseif ($row->news_type == 'videos') {
                                if ($row->videos_type == 'news') {
                                    //get other data (total_like, total_views etc..)
                                    $row2 = $this->getNewsData($row2, $row2->id);
                                    if (!empty($row2->image) && strpos($row2->image, 'news/') === false) {
                                        $image = 'news/' . $row2->image;
                                    } else {
                                        $image = $row2->image;
                                    }
                                    $row2->image = url(Storage::url($image));
                                    if ($row2->content_type == 'video_upload') {
                                        if (!empty($row2->content_value) && strpos($row2->content_value, 'news_video/') === false) {
                                            $content_value = 'news_video/' . $row2->content_value;
                                        } else {
                                            $content_value = $row2->content_value;
                                        }
                                        $row2->content_value = url(Storage::url($content_value));
                                    }
                                    $img = [];
                                    $img = News_image::select('other_image')->select('id')->where('news_id', $row2->id)->get();
                                    for ($k = 0; $k < count($img); $k++) {
                                        $img[$k]->other_image = $img[$k]->other_image ? $img[$k]->other_image : '';
                                        $img[$k]->id = $img[$k]->id;
                                    }
                                    $row2->images = $img;
                                } elseif ($row->videos_type == 'breaking_news') {
                                    if (!empty($row2->image) && strpos($row2->image, 'breaking_news/') === false) {
                                        $image = 'breaking_news/' . $row2->image;
                                    } else {
                                        $image = $row2->image;
                                    }
                                    $row2->image = url(Storage::url($image));
                                    if ($row2->content_type == 'video_upload') {
                                        if (!empty($row2->content_value) && strpos($row2->content_value, 'breaking_news_video/') === false) {
                                            $content_value = 'breaking_news_video/' . $row2->content_value;
                                        } else {
                                            $content_value = $row2->content_value;
                                        }
                                        $row2->content_value = url(Storage::url($content_value));
                                    }
                                    $row2->total_views = BreakingNewsView::where('breaking_news_id', $row2->id)->count('id');
                                }
                            } elseif ($row->news_type == 'rss_feeds_news') {
                                // Format RSS feed items
                                // RSS feed images are typically full URLs from external sources, keep as is
                                if (empty($row2->image)) {
                                    $row2->image = '';
                                }

                                // Ensure link is properly formatted as content_value
                                if (!empty($row2->link)) {
                                    $row2->content_value = $row2->link;
                                }

                                // Set default values for RSS items (already set during parsing, but ensure they exist)
                                $row2->total_like = $row2->total_like ?? 0;
                                $row2->total_views = $row2->total_views ?? 0;
                                $row2->total_comments = $row2->total_comments ?? 0;
                                $row2->is_bookmark = $row2->is_bookmark ?? 0;
                                $row2->images = $row2->images ?? [];
                            }
                        }
                        $total1 = $result_count;
                        // dd($data[$key]->news_type);
                        // Log::info("news_type: " . $row->news_type);
                        $data[$key]->news_type = $data[$key]->is_based_on_user_choice == '1' ? 'user_choice' : $data[$key]->news_type;
                        $content = $data[$key]->is_based_on_user_choice == '1' ? 'news' : $data[$key]->news_type;
                        $content_total = $data[$key]->is_based_on_user_choice == '1' ? 'news_total' : $data[$key]->news_type . '_total';
                        $data[$key]->$content_total = $total1;
                        $data[$key]->$content = $results;
                        $section_id = $data[$key]->id;
                        $ad_space = AdSpaces::where('ad_featured_section_id', $section_id)->where('status', 1)->latest()->first();
                        if (!empty($ad_space)) {
                            $row->ad_spaces = $ad_space;
                        }
                    } else {
                        $content = $data[$key]->news_type;
                        $content_total = $data[$key]->news_type . '_total';
                        $data[$key]->$content_total = 0;
                        $data[$key]->$content = $results;
                    }
                }

                return ResponseService::successResponse(__('Data fetched successfully'), [
                    'total' => $total,
                    'data' => $data,
                ]);
            } else {
                return ResponseService::errorResponse(__('No Data Found'), null, config('constants.RESPONSE_CODE.NOT_FOUND'), null);
            }
        } catch (Exception $e) {

            ResponseService::logErrorResponse($e, 'API Controller -> getFeaturedSections');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function getLiveStreaming(Request $request)
    {
        try {
            $languageId = $this->resolveLanguageId($request);

            $offset = $request->offset ?? 0;
            $limit  = $request->limit ?? 10;
            $orderBy = $request->order_by ?? 'id';
            $sortBy = $request->sort_by ?? 'DESC';
            $slug = $request->filled('slug') ? $request->slug : null;

            $query = LiveStreaming::where('language_id', $languageId);
            if($slug) {
                $query->search($slug);
            }

            $total = $query->clone()->count();

            if($total == 0) {
                return ResponseService::errorResponse(
                    'No Data Found', null,
                    config('constants.RESPONSE_CODE.NOT_FOUND'), null
                );
            }

            // here need to add liveStreaming['slug'] on the basis of title
            // using generateSlugFromTitle function

            $liveStreaming = $query->safeOrder($orderBy, $sortBy)->skip($offset)->take($limit)->get();
            $liveStreaming->map(function ($item) {
                    $item->slug = $this->generateSlugFromTitle($item->title);
                    return $item;
                });
            $liveStreaming = $liveStreaming->values();

            return ResponseService::successResponse(__('Data fetched successfully'), [
                'total' => $total,
                'data' => $liveStreaming,
            ]);

        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, 'API Controller -> getLiveStreaming');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function getVideos(Request $request)
    {
        try {

            $language_id = $this->resolveLanguageId($request);
            $slug = $request->slug ?? null;

            $offset = $request->offset ?? 0;
            $limit = $request->limit ?? 10;
            $latitude = $request->latitude ?? 0;
            $longitude = $request->longitude ?? 0;
            $user_id = Auth::check() ? Auth::user()->id : 0;
            $source_type = $request->source_type ?? null;

            // Get news videos
            $res = DB::table('tbl_news')
                ->selectRaw('tbl_news.*, tbl_category.category_name, tbl_category.slug as category_slug, tbl_location.latitude, tbl_location.longitude, "news" as source_type, SQRT(POW(111.2* (tbl_location.latitude - ?), 2) + POW(111.2 * (? - tbl_location.longitude) * COS(tbl_location.latitude / 57.3), 2)) AS distance', [$latitude, $longitude], )
                ->leftJoin('tbl_category', 'tbl_news.category_id', '=', 'tbl_category.id')
                ->leftJoin('tbl_subcategory', 'tbl_news.subcategory_id', '=', 'tbl_subcategory.id')
                ->leftJoin('tbl_location', 'tbl_news.location_id', '=', 'tbl_location.id')
                // ->where('tbl_news.published_date', '<=', $this->toDate)
                ->where(function ($q1) {
                    $q1->where(function ($subq) {
                        $subq->whereNotNull('tbl_news.published_date')
                            ->where('tbl_news.published_date', '<=', $this->toDate);
                    })->orWhere(function ($subq) {
                        $subq->whereNull('tbl_news.published_date')
                            ->whereDate('tbl_news.created_at', '<=', $this->toDate);
                    });
                })
                ->where('tbl_news.status', 1)->where('tbl_news.language_id', $language_id)
                ->where(function ($q1) {
                    $q1->where('tbl_news.show_till', '>=', $this->toDate)->orWhere('tbl_news.show_till', '0000-00-00');
                })
                ->whereIn('content_type', ['video_upload', 'video_youtube', 'video_other']);

            if ($slug) {
                $res->where('tbl_news.slug', $slug);
            }
            if ($request->category_slug) {
                $res->where('tbl_category.slug', $request->category_slug);
            }

            if (isset($latitude) && isset($longitude) && $latitude != null && $longitude != null) {
                $res->orderByRaw('CASE WHEN distance IS NULL THEN 1 ELSE 0 END, distance ASC')->where(function ($q2) {
                    $q2->having(DB::raw('distance <' . $this->nearest_location_measure . ' OR tbl_news.location_id=. 0'));
                });
            } else {
                $res->orderBy('tbl_news.id', 'DESC');
            }

            // Source type filter for news
            if ($source_type && $source_type === 'news') {
                $totalNews = $res->clone()->count('tbl_news.id');
                $totalBreakingNews = 0;
                $total = $totalNews;

                if ($total) {
                    $data = $res->clone()->limit($limit)->offset($offset)->get();
                    // Process news items
                    foreach ($data as $item) {
                        if (!empty($item->image) && strpos($item->image, 'news/') === false) {
                            $item->image = 'news/' . $item->image;
                        }
                        $item->image = url(Storage::url($item->image));
                        if ($item->content_type == 'video_upload') {
                            if (!empty($item->content_value) && strpos($item->content_value, 'news_video/') === false) {
                                $content_value = 'news_video/' . $item->content_value;
                            } else {
                                $content_value = $item->content_value;
                            }
                            $item->content_value = $item->content_value ? url(Storage::url($content_value)) : '';
                        }
                        $news_like = News_like::where('news_id', $item->id);
                        $item->like = $news_like->clone()->where('status', 1)->where('user_id', $user_id)->count('id');
                        $item->total_like = $news_like->clone()->where('status', 1)->count('id');
                        $item->total_views = News_view::where('news_id', $item->id)->count('id');
                    }

                    return ResponseService::successResponse(__('Data fetched successfully'), [
                        'total' => $total,
                        'data' => $data,
                    ]);

                }

                return ResponseService::errorResponse(__('No Data Found'), null, config('constants.RESPONSE_CODE.NOT_FOUND'), null);
            }

            // Get breaking news videos - only if not filtering by category_slug
            $includeBreakingNews = !$request->has('category_slug');
            $totalBreakingNews = 0;
            $breakingNewsData = collect([]);

            if ($includeBreakingNews) {

                $breakingNews = DB::table('tbl_breaking_news')
                    ->select('tbl_breaking_news.*', DB::raw('"breaking_news" as source_type'))
                    ->where('tbl_breaking_news.language_id', $language_id)
                    ->whereIn('tbl_breaking_news.content_type', ['video_upload', 'video_youtube', 'video_other']);

                if ($slug) {
                    $breakingNews->where('tbl_breaking_news.slug', $slug);
                }

                // Source type filter for breaking news
                if ($source_type && $source_type === 'breaking_news') {
                    $totalBreakingNews = $breakingNews->clone()->count('tbl_breaking_news.id');
                    $total = $totalBreakingNews;

                    if ($total) {
                        $data = $breakingNews->clone()->orderBy('tbl_breaking_news.id', 'DESC')
                            ->limit($limit)->offset($offset)->get();

                        // Process breaking news items
                        foreach ($data as $item) {
                            if (!empty($item->image) && strpos($item->image, 'breaking_news/') === false) {
                                $item->image = 'breaking_news/' . $item->image;
                            }
                            $item->image = url(Storage::url($item->image));
                            if ($item->content_type == 'video_upload') {
                                if (!empty($item->content_value) && strpos($item->content_value, 'breaking_news_video/') === false) {
                                    $content_value = 'breaking_news_video/' . $item->content_value;
                                } else {
                                    $content_value = $item->content_value;
                                }
                                $item->content_value = $item->content_value ? url(Storage::url($content_value)) : '';
                            }
                            $item->category_name = null;
                            $item->category_slug = null;
                            $item->like = 0;
                            $item->total_like = 0;
                            $item->total_views = BreakingNewsView::where('breaking_news_id', $item->id)->count('id');
                        }

                        return ResponseService::successResponse(__('Data fetched successfully'), [
                            'total' => $total,
                            'data' => $data,
                        ]);
                    }

                    return ResponseService::errorResponse(__('No Data Found'), null, config('constants.RESPONSE_CODE.NOT_FOUND'), null);
                }

                //
                // If we get here, we're including both types without a source_type filter
                $totalBreakingNews = $breakingNews->clone()->count('tbl_breaking_news.id');
                $breakingNewsData = $breakingNews->clone()->orderBy('tbl_breaking_news.id', 'DESC')->get();
            }


            // Get live streaming videos
            $includeLiveStreaming = !$request->has('category_slug');
            $totalLiveStreaming = 0;
            $liveStreamingData = collect([]);


            if ($includeLiveStreaming) {

                $liveStreaming = LiveStreaming::withCommonFields()->where('language_id', $language_id);
                // $liveStreaming = DB::table('tbl_live_streaming')
                //     ->select(
                //         'tbl_live_streaming.id',
                //         'tbl_live_streaming.title',
                //         'tbl_live_streaming.image', // getting storage URL
                //         'tbl_live_streaming.type as content_type',
                //         'tbl_live_streaming.url as content_value',
                //         'tbl_live_streaming.created_at as date',
                //         'tbl_live_streaming.created_at as published_date',
                //         DB::raw('"live_streaming" as source_type'),
                //         DB::raw('NULL as description'),
                //         DB::raw('NULL as category_id'),
                //         DB::raw('NULL as subcategory_id'),
                //         DB::raw('NULL as tag_id'),
                //         DB::raw('NULL as category_name'),
                //         DB::raw('NULL as category_slug'),
                //         DB::raw('NULL as slug')
                //     )
                //     ->where('tbl_live_streaming.language_id', $language_id);

                // Source type filter for live streaming
                if ($source_type && $source_type === 'live_streaming') {
                    $totalLiveStreaming = $liveStreaming->clone()->count('tbl_live_streaming.id');
                    $total = $totalLiveStreaming;

                    if ($total) {
                        $data = $liveStreaming->clone()->orderBy('tbl_live_streaming.id', 'DESC')
                            ->limit($limit)->offset($offset)->get();

                        // Process live streaming items
                        foreach ($data as $item) {
                            // Generate slug from title
                            $item->slug = $this->generateSlugFromTitle($item->title);
                            // Image is already processed by the accessor method in the model
                            $item->like = 0;
                            $item->total_like = 0;
                            $item->total_views = 0; // Live streaming doesn't have view counts
                        }

                        // Filter by slug if provided
                        if ($slug) {
                            $data = $data->filter(function ($item) use ($slug) {
                                return $item->slug === $slug;
                            })->values();
                            $total = $data->count();

                            if ($total === 0) {
                                $response = [
                                    'error' => true,
                                    'message' => 'No Data Found',
                                ];
                                return response()->json($response);
                            }
                        }
                        return ResponseService::successResponse(__('Data fetched successfully'), [
                            'total' => $total,
                            'data' => $data,
                        ]);
                    }

                    return ResponseService::errorResponse(__('No Data Found'), null, config('constants.RESPONSE_CODE.NOT_FOUND'), null);
                }

                // If we get here, we're including all types without a source_type filter
                $totalLiveStreaming = $liveStreaming->clone()->count('tbl_live_streaming.id');
                $liveStreamingData = $liveStreaming->clone()->orderBy('tbl_live_streaming.id', 'DESC')->get();

                // Generate slug for each live streaming item
                foreach ($liveStreamingData as $item) {
                    $item->slug = $this->generateSlugFromTitle($item->title);
                }

                // Filter live streaming data by slug if provided
                if ($slug) {
                    $liveStreamingData = $liveStreamingData->filter(function ($item) use ($slug) {
                        return $item->slug === $slug;
                    })->values();
                    $totalLiveStreaming = $liveStreamingData->count();
                }
            }


            // Count and get results
            $totalNews = $res->clone()->count('tbl_news.id');
            $total = $totalNews + $totalBreakingNews + $totalLiveStreaming;


            if ($total) {
                // Get news data
                $newsData = $res->clone()->get();

                // Combine results
                $allData = $newsData->concat($breakingNewsData)->concat($liveStreamingData);

                // Sort by ID descending
                $sortedData = $allData->sortByDesc('id')->values();

                // Apply pagination
                $data = $sortedData->slice($offset, $limit)->values();

                // Process each item
                foreach ($data as $item) {
                    if ($item->source_type === 'news') {
                        // Process news videos
                        if (!empty($item->image) && strpos($item->image, 'news/') === false) {
                            $item->image = 'news/' . $item->image;
                        }
                        $item->image = url(Storage::url($item->image));
                        if ($item->content_type == 'video_upload') {
                            if (!empty($item->content_value) && strpos($item->content_value, 'news_video/') === false) {
                                $content_value = 'news_video/' . $item->content_value;
                            } else {
                                $content_value = $item->content_value;
                            }
                            $item->content_value = $item->content_value ? url(Storage::url($content_value)) : '';
                        }
                        $news_like = News_like::where('news_id', $item->id);
                        $item->like = $news_like->clone()->where('status', 1)->where('user_id', $user_id)->count('id');
                        $item->total_like = $news_like->clone()->where('status', 1)->count('id');
                        $item->total_views = News_view::where('news_id', $item->id)->count('id');
                    } else if ($item->source_type === 'breaking_news') {
                        // Process breaking news videos
                        if (!empty($item->image) && strpos($item->image, 'breaking_news/') === false) {
                            $item->image = 'breaking_news/' . $item->image;
                        }
                        $item->image = url(Storage::url($item->image));
                        if ($item->content_type == 'video_upload') {
                            if (!empty($item->content_value) && strpos($item->content_value, 'breaking_news_video/') === false) {
                                $content_value = 'breaking_news_video/' . $item->content_value;
                            } else {
                                $content_value = $item->content_value;
                            }
                            $item->content_value = $item->content_value ? url(Storage::url($content_value)) : '';
                        }
                        $item->category_name = null;
                        $item->category_slug = null;
                        // $item->like = 0;
                        // $item->total_like = 0;
                        $item->total_views = BreakingNewsView::where('breaking_news_id', $item->id)->count('id');
                    } else if ($item->source_type === 'live_streaming') {
                        // Live streaming items already have their content_value field set to the URL
                        // Note: The image is already processed by the accessor method in the model
                        $item->category_name = null;
                        $item->category_slug = null;
                        $item->like = 0;
                        $item->total_like = 0;
                        $item->total_views = 0; // Live streaming doesn't have view counts
                    }
                }

                return ResponseService::successResponse(__('Data fetched successfully'), [
                    'total' => $total,
                    'data' => $data,
                ]);
            } else {
                return ResponseService::errorResponse(__('No Data Found'), null, config('constants.RESPONSE_CODE.NOT_FOUND'), null);
            }
        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, 'API Controller -> getLiveStreaming');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function getWebSeoPages(Request $request)
    {
        try {
            $language_id = $this->resolveLanguageId($request);

            $offset = $request->offset ?? 0;
            $limit = $request->limit ?? 10;
            $orderBy = $request->order_by ?? 'id';
            $sortBy = $request->sort_by ?? 'DESC';
            $query = WebSeoPages::where('language_id', $language_id);

            if ($request->filled('type')) {
                $query->where('page_type', $request->type);
            }

            $total = $query->clone()->count();
            if($total == 0) {
                return ResponseService::errorResponse(__('No Data Found'), null, config('constants.RESPONSE_CODE.NOT_FOUND'), null);
            }

            $webSeoPages = $query->safeOrder($orderBy, $sortBy)->skip($offset)->take($limit)->get();

            return ResponseService::successResponse(__('Data fetched successfully'), [
                'total' => $total,
                'data' => $webSeoPages,
            ]);
        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, 'API Controller -> getWebSeoPages');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function getNotification(Request $request)
    {
        try {
            $language_id = $this->resolveLanguageId($request);
            $offset = $request->offset ?? 0;
            $limit = $request->limit ?? 10;
            $orderBy = $request->order_by ?? 'id';
            $sortBy = $request->sort_by ?? 'DESC';
            $type = $request->filled('type') ? $request->type : null;

            $query = SendNotification::with([
                'news' => function ($query) {
                    $query->select('id', 'title', 'slug', 'status');
                },
                'category:id,category_name',
            ])
            ->where('language_id', $language_id)
            ->when($type && $type === 'category', function ($query) {
                $query->whereHas('news', function ($q) {
                    $q->where('status', 1);
                });
            });

            $total = $query->clone()->count();

            if ($total == 0) {
                return ResponseService::errorResponse(__('No Data Found'), null, config('constants.RESPONSE_CODE.NOT_FOUND'), null);
            }

            $notifications = $query->safeOrder($orderBy, $sortBy)->skip($offset)->take($limit)->get();
            // $notifications = $query->safeOrder($orderBy, $sortBy)->paginate(); // add in future update

            return ResponseService::successResponse(__('Data fetched successfully'), [
                'total' => $total,
                'data' => $notifications,
            ]);
        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, 'API Controller -> getNotification');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function getTag(Request $request)
    {
        try {
            $languageId = $this->resolveLanguageId($request);
            $offset = $request->offset ?? 0;
            $limit = $request->limit ?? 10;
            $orderBy = $request->order_by ?? 'id'; // column
            $sortBy = $request->sort_by ?? 'DESC'; // direction (ASC or DESC)

            $query = Tag::where('language_id', $languageId)
                    ->when($request->filled('slug'), function ($q) use ($request) {
                        $q->where('slug', $request->slug);
                    });


            $total = $query->clone()->count();

            if ($total == 0) {
                return ResponseService::errorResponse(
                    __('No Data Found'),
                    null,
                    config('constants.RESPONSE_CODE.NOT_FOUND'),
                    null
                );
            }


            // Get paginated result directly
            $tags = $query->safeOrder($orderBy, $sortBy)
            ->skip($offset)
            ->take($limit)
            ->get();

            return ResponseService::successResponse(
                __('Data fetched successfully'),
                [
                    'total' => $total,
                    'data'  => $tags,
                ]
            );

        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, 'API Controller -> getTag');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function getSubcategoryByCategory(Request $request)
    {
        try {
            $language_id = $this->resolveLanguageId($request);
            $validator = Validator::make($request->all(), [
                'category_id' => ['required', 'numeric'],
            ]);
            if ($validator->fails()) {
                ResponseService::errorResponse($validator->errors()->first(), null, config('constants.RESPONSE_CODE.VALIDATION_ERROR'), $validator->errors());
            }
            $category_id = $request->category_id;
            $language_id = $language_id;
            $res = SubCategory::with('category:id,category_name')->where('language_id', $language_id)->where('category_id', $category_id)->orderBy('row_order', 'ASC')->get();

            if ($res->isEmpty()) {

                return ResponseService::errorResponse(__('No Data Found'), null, config('constants.RESPONSE_CODE.NOT_FOUND'), null);

            }

            return ResponseService::successResponse(__('Data fetched successfully'), $res);
        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, 'API Controller -> getSubcategoryByCategory');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function getCategory(Request $request)
    {
        try {
            // Resolve language_id from language_code (preferred) or language_id (backward compatible)
            $languageId = $this->resolveLanguageId($request);

            $offset   = $request->offset ?? 0;
            $limit    = $request->limit ?? 10;
            $orderBy  = $request->order_by ?? 'row_order';
            $sortBy   = $request->sort_by ?? 'ASC';

            // Build query
            $query = Category::with('sub_categories')
            ->where('language_id', $languageId)
            ->when($request->slug, function ($q) use ($request) {
                $q->where('slug', $request->slug);
            });

            // Get total BEFORE pagination
            $total = $query->clone()->count();

            if ($total == 0) {
                return ResponseService::errorResponse(
                    'No Data Found',
                    null,
                    config('constants.RESPONSE_CODE.NOT_FOUND'),
                    null
                );
            }
            // Apply pagination
            $categories = $query->safeOrder($orderBy, $sortBy)
                        ->skip($offset)
                        ->take($limit)
                        ->get();


            return ResponseService::successResponse(
                __('Data fetched successfully'),
                [
                    'total' => $total,
                    'data'  => $categories,
                ]
            );
        } catch (Exception $e) {
            ResponseService::errorResponse($e->getMessage(), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function getLocation(Request $request)
    {
        try {
            $offset = $request->offset ?? 0;
            $limit = $request->limit ?? 10;
            $orderBy = $request->order_by ?? 'id';
            $sortBy = $request->sort_by ?? 'DESC';

            $total = Location::count('id');
            if ($total == 0) {
                return ResponseService::errorResponse(__('No Data Found'), null, config('constants.RESPONSE_CODE.NOT_FOUND'), null);
            }

            $data = Location::select('id', 'location_name', 'latitude', 'longitude')->safeOrder($orderBy, $sortBy)->skip($offset)->take($limit)->get();


            ResponseService::successResponse(__('Data fetched successfully'), [
                'total' => $total,
                'data' => $data,
            ]);

        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, 'API Controller -> getLocation');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function getPolicyPages(Request $request)
    {
        try {
            $languageId = $this->resolveLanguageId($request);

            // Single query: fetch both page types, prioritize language-specific over fallback
            $pages = Pages::select('id', 'language_id', 'title', 'page_content', 'page_type')
                ->whereIn('page_type', ['terms-condition', 'privacy-policy'])
                ->orderByRaw('CASE WHEN language_id = ? THEN 0 ELSE 1 END', [$languageId])
                ->get()
                ->groupBy('page_type');

            $terms_policy   = $pages->get('terms-condition')?->first();
            $privacy_policy = $pages->get('privacy-policy')?->first();

            if ($terms_policy || $privacy_policy) {
                ResponseService::successResponse(__('Data fetched successfully'), [
                    'terms_policy' => $terms_policy,
                    'privacy_policy' => $privacy_policy,
                ]);
            } else {
                ResponseService::errorResponse(__('No Data Found'), null, config('constants.RESPONSE_CODE.NOT_FOUND'), null);
            }
        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, 'API Controller -> getPolicyPages');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function getPages(Request $request)
    {
        try {
            // validation for language id or code done in trait
            $languageId = $this->resolveLanguageId($request);

            $offset = $request->offset ?? 0;
            $limit = $request->limit ?? 10;
            $orderBy = $request->order_by ?? 'id'; // column name
            $sortBy = $request->sort_by ?? 'DESC'; // direction (ASC or DESC)

            $query = Pages::where('language_id', $languageId)->where('status', 1);
            if ($request->filled('slug')) {
                $query->where('slug', $request->slug);
            }

            // getting total
            $total = $query->clone()->count();

            if($total == 0) {
                return ResponseService::errorResponse(
                    'No Data Found', null,
                    config('constants.RESPONSE_CODE.NOT_FOUND'), null
                );
            }

            $pages = $query->safeOrder($orderBy, $sortBy)->skip($offset)->take($limit)->get();
            return ResponseService::successResponse(__('Data fetched successfully'), [
                'total' => $total,
                'data' => $pages,
            ]);

        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, 'API Controller -> getPages');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function getLanguageJsonData(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required',
            ]);
            if ($validator->fails()) {
                return ResponseService::validationError($validator->errors()->first());
            }

            $code = $request->code;
            $jsonFilePath = storage_path('app/public/language/' . $code . '.json');
            if(!file_exists($jsonFilePath)){
                return ResponseService::errorResponse(__('No Data Found'), null, config('constants.RESPONSE_CODE.NOT_FOUND'), null);
            }

            $jsonFile = file_get_contents($jsonFilePath);
            $jsonData = json_decode($jsonFile, true);

            ResponseService::successResponse(__('Data fetched successfully'), $jsonData);
        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, 'API Controller -> getLanguageJsonData');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function getLanguagesList(Request $request)
    {
        try {
            // $offset = $request->offset ?? 0;
            // $limit = $request->limit ?? 10;
            // ->skip($offset)->take($limit)
            $languages = Language::select('id', 'language', 'code', 'status', 'isRTL', 'image', 'display_name')->where('status', 1)->get();

            if ($languages->isEmpty()) {
                return ResponseService::errorResponse(
                    __('No Data Found'),
                    null,
                    config('constants.RESPONSE_CODE.NOT_FOUND'),
                    null
                );
            }

            // Get default language ID from settings
            $defaultLangId = Settings::where('type', 'default_language')
            ->value('message');

            // Determine default language
            $default_language = $languages->firstWhere('id', $defaultLangId)
                                ?? $languages->firstWhere('code', 'en')
                                ?? $languages->first(); // final fallback

            return ResponseService::successResponse(
                __('Data fetched successfully'),
                [
                    'default_language' => $default_language,
                    'data' => $languages,
                ]
            );

        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, 'API Controller -> getLanguagesList');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    public function getSettings()
    {
        try {
            $types = ['system_timezone', 'category_mode', 'subcategory_mode', 'breaking_news_mode', 'live_streaming_mode', 'rss_feed_mode', 'comments_mode', 'weather_mode', 'location_news_mode', 'nearest_location_measure', 'video_type_preference', 'maintenance_mode', 'mobile_login_mode', 'country_code', 'auto_delete_expire_news_mode', 'app_version', 'appstore_app_id', 'shareapp_text', 'ads_type', 'in_app_ads_mode', 'ios_in_app_ads_mode', 'ios_ads_type', 'google_rewarded_video_id', 'google_interstitial_id', 'google_banner_id', 'google_native_unit_id', 'ios_google_rewarded_video_id', 'ios_google_interstitial_id', 'ios_google_banner_id', 'ios_google_native_unit_id', 'unity_rewarded_video_id', 'unity_interstitial_id', 'unity_banner_id', 'android_game_id', 'ios_unity_rewarded_video_id', 'ios_unity_interstitial_id', 'ios_unity_banner_id', 'ios_game_id', 'force_update_app_mode', 'android_app_version', 'ios_app_version', 'google_gemini_api_key', 'google_app_open_unit_id', 'ios_google_app_open_unit_id'];
            $res = Settings::whereIn('type', $types)->pluck('message', 'type')->toArray();
            $res['google_gemini_api_key'] = base64_encode($res['google_gemini_api_key']);

            if (!empty($res)) {
                $setting = Settings::where('type', 'default_language')->pluck('message')->first();
                $default_lang = $setting ?? 0;
                $language = Language::select('id', 'language', 'code', 'status', 'isRTL', 'image', 'display_name');
                if ($default_lang == 0) {
                    $default_language = $language->clone()->where('code', 'en')->first();
                } else {
                    $default_language = $language->clone()->where('id', $default_lang)->first();
                }
                $res['default_language'] = $default_language;

                $web_setting = WebSetting::pluck('message', 'type')->toArray();
                if (!empty($web_setting)) {
                    $web_setting['light_header_logo'] = asset('storage/' . $web_setting['light_header_logo']);
                    $web_setting['light_footer_logo'] = asset('storage/' . $web_setting['light_footer_logo']);
                    $web_setting['light_placeholder_image'] = isset($web_setting['light_placeholder_image']) ? asset('storage/' . $web_setting['light_placeholder_image']) : '';
                    $web_setting['dark_header_logo'] = isset($web_setting['dark_header_logo']) ? asset('storage/' . $web_setting['dark_header_logo']) : '';
                    $web_setting['dark_footer_logo'] = isset($web_setting['dark_footer_logo']) ? asset('storage/' . $web_setting['dark_footer_logo']) : '';
                    $web_setting['dark_placeholder_image'] = isset($web_setting['dark_placeholder_image']) ? asset('storage/' . $web_setting['dark_placeholder_image']) : '';
                    $web_setting['favicon_icon'] = isset($web_setting['favicon_icon']) ? asset('storage/' . $web_setting['favicon_icon']) : '';
                }
                $res['web_setting'] = $web_setting;
                $res['social_media'] = SocialMedia::select('id', 'image', 'link')->get();

                return ResponseService::successResponse(__('Data fetched successfully'), $res);
            } else {
                return ResponseService::errorResponse(__('No Data Found'), null, config('constants.RESPONSE_CODE.NOT_FOUND'), null);
            }
        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, 'API Controller -> getSettings');
            return ResponseService::errorResponse(__('Something Went Wrong'), null, config('constants.RESPONSE_CODE.EXCEPTION_ERROR'), $e);
        }
    }

    function getCommentData($from, $user_id, $news_id)
    {
        $res = Comments::with('user:id,name,profile')->where('news_id', $news_id)->where('parent_id', 0)->where('status', 1);
        $total = $res->clone()->count('id');
        if ($total) {
            $offset = $request->offset ?? 0;
            $limit = $request->limit ?? 10;
            $data = $res->clone()->orderBy('id', 'DESC')->skip($offset)->take($limit)->get();
            for ($i = 0; $i < count($data); $i++) {
                $comment_like = CommentsLike::where('comment_id', $data[$i]->id);
                $data[$i]->total_like = $comment_like->clone()->where('status', 1)->count('id');
                $data[$i]->total_dislike = $comment_like->clone()->where('status', 2)->count('id');
                $data[$i]->like = $comment_like->clone()->where('status', 1)->where('user_id', $user_id)->count('id');
                $data[$i]->dislike = $comment_like->clone()->where('status', 2)->where('user_id', $user_id)->count('id');

                $data[$i]->reply = $data3 = [];
                $data3 = Comments::with('user')->where('news_id', $news_id)->where('parent_id', $data[$i]->id)->where('status', 1)->orderBy('id', 'ASC')->get();
                for ($j = 0; $j < count($data3); $j++) {
                    $comment_like1 = CommentsLike::where('comment_id', $data3[$j]->id);
                    $data3[$j]->total_like = $comment_like1->clone()->where('status', 1)->count('id');
                    $data3[$j]->total_dislike = $comment_like1->clone()->where('status', 2)->count('id');
                    $data3[$j]->like = $comment_like1->clone()->where('status', 1)->where('user_id', $user_id)->count('id');
                    $data3[$j]->dislike = $comment_like1->clone()->where('status', 2)->where('user_id', $user_id)->count('id');
                }
                $data[$i]->reply = $data3;
            }

            $message = __('Data fetched successfully');
            if ($from == 'setComment') {
                $message = __('Comment successfully');
            } else if ($from == 'setCommentLikeDislike') {
                $message = __('updated Successfully');
            }

            return ResponseService::successResponse($message, [
                'total' => $total,
                'data' => $data,
            ]);
        } else {
            return ResponseService::errorResponse(__('No Data Found'), null, config('constants.RESPONSE_CODE.NOT_FOUND'), null);
        }
    }

    function getNewsData($row, $news_id)
    {
        $user_id = Auth::check() ? Auth::user()->id : 0;
        $news_like = News_like::where('news_id', $news_id);
        $row->like = $news_like->clone()->where('status', 1)->where('user_id', $user_id)->count('id');
        $row->total_like = $news_like->clone()->where('status', 1)->count('id');
        // $row->dislike = $news_like->clone()->where('status', 2)->where('user_id', $user_id)->count('id');
        // $row->total_dislike = $news_like->clone()->where('status', 2)->count('id');
        $news_bookmark = Bookmark::where('news_id', $news_id);
        $row->total_bookmark = $news_bookmark->clone()->count('id');
        $row->bookmark = $news_bookmark->clone()->where('user_id', $user_id)->count('id');
        $row->total_views = News_view::where('news_id', $news_id)->count('id');
        $row->tag_name = '';
        $row->tag = [];
        if (isset($row->tag_id) && $row->tag_id != '') {
            $tagNames = Tag::whereIn('id', explode(',', $row->tag_id))->distinct()->pluck('tag_name')->implode(',');
            $row->tag_name = $tagNames;
            $row->tag = Tag::select('id', 'tag_name', 'slug')->whereIn('id', explode(',', $row->tag_id))->get();
        }
        $row->is_expired = 0;
        if ($row->show_till && $row->show_till != '0000-00-00') {
            $row->is_expired = date('Y-m-d') > $row->show_till ? 1 : 0;
        }
        return $row;
    }

    private function generateSlugFromTitle($title)
    {
        // Convert to lowercase and replace spaces with hyphens
        $slug = strtolower($title);
        // Remove special characters
        $slug = preg_replace('/[^a-z0-9-]/', '', str_replace(' ', '-', $slug));
        // Make sure it's not empty
        return empty($slug) ? 'video-' . time() : $slug;
    }

    public function becomeAuthor(Request $request)
    {

        // $validator = Validator::make($request->all(), [

        // ]);

        // if ($validator->fails()) {
        //     return ResponseService::validationError($validator->errors()->first());
        // }

        try {

            $userId = Auth::user()->id;

            if (Auth::user()->is_author == 1) {
                return ResponseService::successResponse('You are already an author');
            }


            // Check if user already has an author record
            $existingAuthor = Author::where('user_id', $userId)->first();

            if ($existingAuthor) {
                // Check the status of existing request
                if ($existingAuthor->status === 'pending') {
                    $existingAuthor->update([
                        'status' => 'pending', // Reset to pending for resubmission
                    ]);
                    return ResponseService::successResponse('You already have a pending author request');
                } elseif ($existingAuthor->status === 'approved') {
                    return ResponseService::successResponse('You are already an approved author');
                } elseif ($existingAuthor->status === 'rejected') {

                    // Allow resubmission if previously rejected - update the existing record
                    $existingAuthor->update([
                        'status' => 'pending', // Reset to pending for resubmission
                    ]);
                    return ResponseService::successResponse('Author request resubmitted successfully');
                }
            } else {

                Author::create([
                    'user_id' => $userId,
                    'status' => 'pending',
                ]);
                return ResponseService::successResponse('Author request sent successfully');
            }

            // Create new author request
            // Author::create([
            //     'user_id' => $userId,
            //     'bio' => $request->bio ,
            //     'telegram_link' => $request->telegram_link,
            //     'linkedin_link' => $request->linkedin_link,
            //     'facebook_link' => $request->facebook_link,
            //     'whatsapp_link' => $request->whatsapp_link,
            // ]);

            return ResponseService::successResponse('Author request sent successfully');
        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, "API Controller -> becomeAuthor");
            return ResponseService::errorResponse(__('Something Went Wrong'));
        }
    }

    public function updateAuthorProfile(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'bio' => 'required|string|max:255',
                'telegram_link' => 'required|url|max:255',
                'linkedin_link' => 'required|url|max:255',
                'facebook_link' => 'required|url|max:255',
                'whatsapp_link' => 'required|url|max:255',
            ]);

            if ($validator->fails()) {
                return ResponseService::validationError($validator->errors()->first());
            }

            $userId = Auth::user()->id;
            $author = Author::where('user_id', $userId)->first();
            if (!$author) {
                return ResponseService::errorResponse('Author not found');
            }
            $author->update([
                'bio' => $request->bio,
                'telegram_link' => $request->telegram_link,
                'linkedin_link' => $request->linkedin_link,
                'facebook_link' => $request->facebook_link,
                'whatsapp_link' => $request->whatsapp_link,
            ]);
            return ResponseService::successResponse('Author profile updated successfully');


        } catch (Exception $e) {

            ResponseService::logErrorResponse($e, "API Controller -> updateAuthorProfile");
            return ResponseService::errorResponse(__('Something Went Wrong'));
        }
    }


    public function getAuthorsNews($author_id)
    {
        try {

            $user = User::where(['id' => $author_id, 'is_author' => 1])->first();
            if (!$user) {
                return ResponseService::errorResponse('Author not found', null, 404);
            }
            $author = Author::where(['user_id' => $author_id, 'status' => 'approved'])->first();

            if (!$author) {
                return ResponseService::errorResponse('Author not found', null, 404);
            }

            $userData = $user->toArray();
            $userData['author'] = $author->toArray();

            $news = News::with(
                'category:id,category_name,slug',
                'sub_category:id,subcategory_name',
                'location:id,location_name,latitude,longitude',
                'images',
                // 'tag:id,tag_name,slug',
                'author',
                'comments'
            )
                ->withCount('newsview')
                ->where('user_id', $author_id)
                ->where('is_draft', 0)
                ->paginate(config('constants.PAGINATION.SIX_PER_PAGE'));

            foreach ($news as $item) {
                if (isset($item->tag_id) && $item->tag_id != '') {
                    $tagNames = Tag::whereIn('id', explode(',', $item->tag_id))->distinct()->pluck('tag_name')->implode(',');
                    $item->tag_name = $tagNames;
                }
            }

            $data = [
                'user' => $userData,
                // 'author' => $authorData,
                'news' => $news
            ];


            return ResponseService::successResponse('Author news fetched successfully', $data);
        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, "API Controller -> getAuthorsNews");
            return ResponseService::errorResponse(__('Something Went Wrong'));
        }
    }

    // public function getUserDraftedNews(Request $request, $author_id){
    public function getUserDraftedNews(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'author_id' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                return ResponseService::validationError($validator->errors()->first());
            }

            $user = User::where(['id' => $request->author_id, 'is_author' => 1])->first();
            if (!$user) {
                return ResponseService::errorResponse('Author not found');
            }


            $news = News::with(
                'category:id,category_name,slug',
                'sub_category:id,subcategory_name',
                'location:id,location_name,latitude,longitude',
                'images',
                // 'tag:id,tag_name,slug',
                // 'author:id,name,profile'
            )->where('user_id', $request->author_id)->where('is_draft', 1)->paginate(config('constants.PAGINATION.SIX_PER_PAGE'));


            foreach ($news as $item) {
                if (isset($item->tag_id) && $item->tag_id != '') {
                    $tagNames = Tag::whereIn('id', explode(',', $item->tag_id))->distinct()->pluck('tag_name')->implode(',');
                    $item->tag_name = $tagNames;
                }
            }

            // separating tags by ,
            // $news->each(function ($item) {
            //     $item->tag = [];
            //     if (isset($item->tag_id) && $item->tag_id != '') {
            //         $tagNames = Tag::whereIn('id', explode(',', $item->tag_id))->distinct()->pluck('tag_name')->implode(',');
            //         $item->tag_name = $tagNames;
            //         $item->tag = Tag::select('id', 'tag_name', 'slug')->whereIn('id', explode(',', $item->tag_id))->get();
            //     }
            // });

            $data = [
                'news' => $news
            ];


            return ResponseService::successResponse('Author news fetched successfully', $data);
        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, "API Controller -> getUserDraftedNews");
            return ResponseService::errorResponse(__('Something Went Wrong'));
        }
    }

    public function createTag(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                // 'language_id' => 'required|exists:tbl_languages,id',
                'tag_name' => 'required|string',
            ]);
            if ($validator->fails()) {
                return ResponseService::validationError($validator->errors()->first());
            }

            $languageId = $this->resolveLanguageId($request);

            // not author than not allowed to create tag
            if (Auth::user()->is_author != 1) {
                return ResponseService::errorResponse('You are not allowed to create tag', null, 403);
            }

            $slug = generateUniqueSlug($request->tag_name);

            // check tag exists than response other wise create tag
            $tagCheck = Tag::where(['language_id' => $languageId, 'slug' => $slug])->first();
            if ($tagCheck) {
                // return ResponseService::errorResponse('Tag already exists', null, 400);
                return ResponseService::successResponse('Tag already exists', $tagCheck);
            }

            $tag = Tag::create([
                'tag_name' => $request->tag_name,
                'slug' => $slug,
                'language_id' => $languageId,
            ]);

            return ResponseService::successResponse('Tag created successfully', $tag);

        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, "API Controller -> setTag");
            return ResponseService::errorResponse(__('Something Went Wrong'));
        }
    }

    /**
     * get e news list
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function getENews(Request $request)
    {
        try {
            $languageId = $this->resolveLanguageId($request);
            $perPage = $request->per_page ?? 10;
            $orderBy = $request->order_by ?? 'id';
            $sortBy = $request->sort_by ?? 'DESC';
            $search = $request->search ?? '';

            $eNews = Enews::where('language_id', $languageId)->search($search, $languageId)->safeOrder($orderBy, $sortBy)->paginate($perPage);

            return ResponseService::successResponse('E news fetched successfully', $eNews);
        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, "API Controller -> getENews");
            return ResponseService::errorResponse(__('Something Went Wrong'));
        }
    }


    public function getFeedItems(Request $request)
    {
        try {
            $languageId = $this->resolveLanguageId($request);

            // validation
            $validator = Validator::make($request->all(), [
                'per_page' => ['nullable', 'integer', 'min:1', 'max:100'], // to avoid crash
                'category_ids' => ['nullable', 'string'],
                'source_ids' => ['nullable', 'string'],
            ]);
            if ($validator->fails()) {
                return ResponseService::validationError($validator->errors()->first());
            }


            $perPage = $request->per_page ?? 10;
            $freshness = Carbon::now()->subDays(7);

            // Parse comma-separated IDs
            // $categoryIds = $request->category_ids ? explode(',', $request->category_ids) : null;
            $categoryIds = $request->category_ids
                            ? array_values(array_filter(array_map('intval', explode(',', $request->category_ids))))
                            : null;
            $subCategoryIds = $request->sub_category_ids
                            ? array_values(array_filter(array_map('intval', explode(',', $request->sub_category_ids))))
                            : null;
            // $sourceIds = $request->source_ids ? explode(',', $request->source_ids) : null;
            $sourceIds = $request->source_ids
                            ? array_values(array_filter(array_map('intval', explode(',', $request->source_ids))))
                            : null;

            // Remove non-existent IDs from sourceIds and categoryIds
            if ($categoryIds) {
                $categoryIds = Category::whereIn('id', $categoryIds)
                    ->pluck('id')
                    ->toArray();
            }
            if ($subCategoryIds) {
                $subCategoryIds = Category::whereIn('id', $subCategoryIds)
                    ->pluck('id')
                    ->toArray();
            }

            if ($sourceIds) {
                $sourceIds = RSS::whereIn('id', $sourceIds)
                    ->where('status', 1)
                    ->pluck('id')
                    ->toArray();
            }

            $feedItems = FeedItem::selectedColumns()->with([
                'source:id,language_id,category_id,subcategory_id,tag_id,feed_name,feed_url',
                'source.category:id,slug,category_name',
                'source.sub_category:id,slug,subcategory_name',
            ])
            ->whereHas('source', function ($query) use ($languageId, $categoryIds, $subCategoryIds) {
                $query->where('language_id', $languageId)
                    // ->where('status', 1)
                    ->when($categoryIds, fn($q) => $q->whereIn('category_id', $categoryIds))
                    ->when($subCategoryIds, fn($q) => $q->whereIn('subcategory_id', $subCategoryIds));
            })
            ->when($sourceIds, fn($query) => $query->whereIn('source_id', $sourceIds))
            ->where('published_at', '>=', $freshness)
            ->whereNotNull('published_at')
            ->inRandomOrder()
            ->paginate($perPage);

            // Resolve comma-separated tag_id from source into tags
            foreach ($feedItems as $item) {
                if (isset($item->source->tag_id) && $item->source->tag_id != '') {
                    $tagIds = explode(',', $item->source->tag_id);
                    // $item->source->tag_name = Tag::whereIn('id', $tagIds)->distinct()->pluck('tag_name')->implode(',');
                    $item->source->tag = Tag::select('id', 'tag_name', 'slug')->whereIn('id', $tagIds)->get();
                } else {
                    // $item->source->tag_name = '';
                    $item->source->tag = [];
                }
            }

            return ResponseService::successResponse('RSS feeds fetched successfully', $feedItems);
        } catch (Exception $e) {
            ResponseService::logErrorResponse($e, "API Controller -> getRssFeeds (job)");
            return ResponseService::errorResponse(__('Something Went Wrong'));
        }
    }
}
