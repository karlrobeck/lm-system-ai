import { query } from "@solidjs/router";
import { File } from "./file";

export type Visualization = {
    id: string;
    test_type: "pre" | "post";
    image: File;
    question: string;
    choices: string[];
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
    choices?: string[];
    created_at: string;
    updated_at: string;
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
