"use client";
import React from "react";

const RichTextContent = ({ content, className = "" }) => {
    return (
        <div className={`rich-text-content ${className}`.trim()}>
            <div
                dangerouslySetInnerHTML={{
                    __html: content || "",
                }}
            />
        </div>
    );
};

export default RichTextContent;
