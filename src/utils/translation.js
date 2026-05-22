"use client";
import { store } from "@/components/store/store";
import enTranslations from './locale/en.json';
import frTranslations from './locale/fr.json';

const localeFallbacks = {
    en: enTranslations,
    fr: frTranslations,
};

export const translate = (label) => {
    const langCode =
        store.getState().languages?.currentLanguage?.code || 'fr';
    const langLabel =
        store.getState().languages?.currentLanguageLabels?.data?.[label];

    if (langLabel) {
        return langLabel;
    }

    const fallback =
        localeFallbacks[langCode] || localeFallbacks.fr || enTranslations;
    return fallback[label] ?? enTranslations[label] ?? label;
};