import { query } from "@solidjs/router";
import type { File } from "./file";

export type Visualization = {
    id: string;
    test_type: "pre" | "post";
    image_file: File;
    question: string;
    choices: string; // JSON stringified array
    correct_answer: string;
    context_file: File;
    created_at: string;
    updated_at: string;
};

export type Auditory = {
    id: string;
    test_type: "pre" | "post";
    audio_file: File;
    correct_answer: string;
    context_file: File;
    created_at: string;
    updated_at: string;
};

export type ReadingWriting = {
    id: string;
    test_type: "pre" | "post";
    mode: "reading" | "writing";
    question: string;
    correct_answer: string;
    choices?: string; // JSON stringified array
    created_at: string;
    updated_at: string;
    context_file: File;
};

export const modality = {
    visualization: {
        listByContextFile: query(async (contextFileId: string) => {
            const token = localStorage.getItem("token");
            const response = await fetch(
                `/api/modality/visualizations/context-file/${contextFileId}`,
                {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                },
            );
            return await response.json() as Visualization[];
        }, "visualizationListByContextFile"),
    },
    auditory: {
        listByContextFile: query(async (contextFileId: string) => {
            const token = localStorage.getItem("token");
            const response = await fetch(
                `/api/modality/auditory/context-file/${contextFileId}`,
                {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                },
            );
            return await response.json() as Auditory[];
        }, "auditoryListByContextFile"),
    },
    readingWriting: {
        listByContextFile: query(async (contextFileId: string) => {
            const token = localStorage.getItem("token");
            const response = await fetch(
                `/api/modality/reading-writing/context-file/${contextFileId}`,
                {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                },
            );
            return await response.json() as ReadingWriting[];
        }, "readingWritingListByContextFile"),
    },
};
