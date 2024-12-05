import { query } from "@solidjs/router";

export type User = {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
    level: string;
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

export const getUsers = async () => {
    const response = await fetch("/api/users");
    return await response.json() as User[];
};

export const getUserById = query(async (id: number) => {
    const response = await fetch(`/api/users/${id}`);
    return await response.json() as User;
}, "getUserById");
