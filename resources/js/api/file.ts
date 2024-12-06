import { query } from "@solidjs/router";
import { User } from "./user";

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

export const getFileById = async (id: string) => {
    const response = await fetch(`/api/files/${id}`);
    return await response.json() as File;
};