import { action, query, redirect } from "@solidjs/router";
import type { File } from "./file";

export type User = {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
    level: string;
    has_assessment: number;
    created_at: string;
    updated_at: string;
    files: {
        id: string;
        owner_id: string;
        path: string;
        name: string;
        type: string;
        created_at: string;
        updated_at: string;
    }[];
};

export type Score = {
    id: number;
    correct: number;
    total: number;
    file_id: number;
    user_id: number;
    rank: number;
    is_passed: boolean;
    file:File;
    user:User;
    test_type: 'pre' | 'post';
    modality: 'auditory' | 'reading' | 'visualization' | 'writing';
};

export const getScores = query(async() => {
    const token = localStorage.getItem("token");

    const response = await fetch("/api/scores/", {
        headers: {
            Authorization: `Bearer ${token}`,
        },
    });
    const payload = await response.json();

    return payload as Score[];

},"getScores");

export const getScoresByFileId = query(async (fileId: string) => {
    const token = localStorage.getItem("token");

    const response = await fetch(`/api/scores/${fileId}`, {
        headers: {
            Authorization: `Bearer ${token}`,
        },
    });
    const payload = await response.json();

    console.log(payload);

    return payload as Score[];
}, "getScoresByFileId");

export const getUsers = async () => {
    const response = await fetch("/api/users");
    return await response.json() as User[];
};

export const getUserById = query(async (id: number) => {
    const token = localStorage.getItem("token");
    const response = await fetch(`/api/users/${id}`, {
        headers: {
            Authorization: `Bearer ${token}`,
        },
    });
    return await response.json() as User;
}, "getUserById");

export const getCurrentUser = query(async () => {
    const token = localStorage.getItem("token");
    const response = await fetch("/api/users/me", {
        headers: {
            Authorization: `Bearer ${token}`,
        },
    });
    if(!response.ok) {
        localStorage.removeItem("token");
        throw redirect("/login");
    }
    const data = await response.json();
    return data as User;
}, "getCurrentUser");

export const logout = action(async () => {
    console.log("logout");
    const token = localStorage.getItem("token");
    const response = await fetch("/auth/logout", {
        headers: {
            Authorization: `Bearer ${token}`,
        },
    });
    if (!response.ok) {
        throw new Error(response.statusText);
    }
    localStorage.removeItem("token");
    throw redirect("/login");
});
