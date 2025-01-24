import { action, query, redirect } from "@solidjs/router";

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
