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
    question: string;
    choices: string; // JSON stringified array
    question_index: number;
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

export type AssessmentRanking = {
    id: number;
    user_id: number;
    file_id: number;
    rank: number;
    modality: "reading" | "writing" | "visualization" | "auditory" | "kinesthetic";
    message: string;
    is_failed: boolean;
    pre_test_passed: boolean;
    post_test_passed: boolean;
    created_at: string;
    updated_at: string;
};

export const modality = {
    assessment:{
        getAllRanking:query(async () => {
            const token = localStorage.getItem("token");
            const response = await fetch('/api/assessment/ranking', {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            });
            const payload = await response.json();

            console.log(payload);

            return payload as AssessmentRanking[];
        },"assessmentGetAllRanking"),
    },
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
                `/api/modality/auditory/post/${contextFileId}`,
                {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                },
            );

            const preTest = await preTestResponse.json() as Auditory[];
            const postTest = await postTestResponse.json() as Auditory[];
            console.log(preTest, postTest);
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
    kinesthetic:{
        listByContextFile: query(async (contextFileId: string) => {
            const token = localStorage.getItem("token");
            const preTestResponse = await fetch(
                `/api/modality/kinesthetic/pre/${contextFileId}`,
                {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                },
            );

            const postTestResponse = await fetch(
                `/api/modality/kinesthetic/post/${contextFileId}`,
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
            
        }, "kinestheticListByContextFile"),
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

            const preTest = await preTestResponse.json() as Writing[];
            const postTest = await postTestResponse.json() as Writing[];

            
           return [...preTest,...postTest] as Writing[];
        }, "writingListByContextFile"),
    }
};
