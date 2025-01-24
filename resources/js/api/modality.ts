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

export type Reading = {
    id: string;
    test_type: "pre" | "post";
    question: string;
    choices: string; // JSON stringified array
    question_index: number;
    correct_answer: string;
    file_id: string;
    created_at: string;
    updated_at: string;
}

export type Writing = {
    id: string;
    test_type: "pre" | "post";
    question: string;
    context_answer: string;
    question_index: number;
    created_at: string;
    updated_at: string;
    file_id: string;
}

export const modality = {
    visualization: {
        listByContextFile: query(async (contextFileId: string) => {
            const token = localStorage.getItem("token");
            
            const preTestResponse = await fetch(
                `/api/modality/visualization/pre/${contextFileId}`,
                {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                },
            );

            const postTestResponse = await fetch(
                `/api/modality/visualization/post/${contextFileId}`,
                {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                },
            );

            const preTest = await preTestResponse.json() as Visualization[];
            const postTest = await postTestResponse.json() as Visualization[];

            return [...preTest, ...postTest] as Visualization[];
        }, "visualizationListByContextFile"),
    },
    auditory: {
        listByContextFile: query(async (contextFileId: string) => {
            const token = localStorage.getItem("token");
            const preTestResponse = await fetch(
                `/api/modality/auditory/pre/${contextFileId}`,
                {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                },
            );
            const postTestResponse = await fetch(
                `/api/modality/auditory/pre/${contextFileId}`,
                {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                },
            );

            const preTest = await preTestResponse.json() as Auditory[];
            const postTest = await postTestResponse.json() as Auditory[];

            return [...preTest, ...postTest] as Auditory[];
        }, "auditoryListByContextFile"),
    },
    reading:{
        listByContextFile: query(async (contextFileId: string) => {
            const token = localStorage.getItem("token");
            const preTestResponse = await fetch(
                `/api/modality/reading/pre/${contextFileId}`,
                {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                },
            );

            const postTestResponse = await fetch(
                `/api/modality/reading/post/${contextFileId}`,
                {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                },
            );
            try {
                // join
            const preTest = await preTestResponse.json() as Reading[];
            const postTest = await postTestResponse.json() as Reading[];
            return [...preTest, ...postTest] as Reading[];
            } catch (e) {
                console.error(e);
                return [] as Reading[];
            }
            
        }, "readingListByContextFile"),
    },
    writing: {
        listByContextFile: query(async (contextFileId: string) => {
            const token = localStorage.getItem("token");
            const preTestResponse = await fetch(
                `/api/modality/writing/pre/${contextFileId}`,
                {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                },
            );

            const postTestResponse = await fetch(
                `/api/modality/writing/post/${contextFileId}`,
                {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                },
            );
            // join
            const preTest = await preTestResponse.json() as Writing[];
            const postTest = await postTestResponse.json() as Writing[];
            return [...preTest, ...postTest] as Writing[];
        }, "writingListByContextFile"),
    }
};
