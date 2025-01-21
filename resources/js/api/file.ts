import { action, query, revalidate } from "@solidjs/router";
import type { User } from "./user";

export type File = {
    id: string;
    owner_id: string;
    path: string;
    name: string;
    type: "markdown" | "pdf" | "image" | "audio";
    created_at: string;
    updated_at: string;
    user: User;
};

export const getFileMetadataById = query(async (id: string) => {
    const token = localStorage.getItem("token");
    const response = await fetch(`/api/files/metadata/${id}`, {
        headers: {
            Authorization: `Bearer ${token}`,
        },
    });
    const data = await response.json();

    console.log(data);
    return data as File;
}, "getFileMetadataById");

export const uploadFile = action(async (formData: FormData) => {
    const token = localStorage.getItem("token");
    const csrfToken = document.querySelector('meta[name="csrf-token"]')
    .getAttribute("content");
    const response = await fetch("/api/files/upload", {
        method: "POST",
        headers: {
            Authorization: `Bearer ${token}`,
            "X-CSRF-TOKEN": csrfToken,
        },
        body: formData,
    });
    console.log(await response.text());
    revalidate("getCurrentUser");
})