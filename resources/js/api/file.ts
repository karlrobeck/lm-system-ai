import { action, query, reload, revalidate } from "@solidjs/router";
import type { User } from "./user";
import { modality } from "./modality";
import { showToast } from "~/components/ui/toast"

export type File = {
    id: string;
    owner_id: string;
    path: string;
    name: string;
    type: "markdown" | "pdf" | "image" | "audio";
    is_ready:boolean;
    study_notes:string;
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
    const data = await response.json() as File;

    console.log(data);

    reload({revalidate:modality.reading.listByContextFile.keyFor(data.id)});
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
    if(response.ok) {
        showToast({
            title: "File uploaded",
            description: "The file has been uploaded successfully",
        });
    } else {
        showToast({
            title: "File upload failed",
            description: "There was an error uploading the file",
        });
    }
    console.log(await response.text());
    revalidate("getCurrentUser");
})

export const submitAnswers = action(async (formData:FormData) => {
    const token = localStorage.getItem("token");
    const csrfToken = document.querySelector('meta[name="csrf-token"]')
    .getAttribute("content");
    fetch("/api/scores/submit", {
        method: "POST",
        headers: {
            Authorization: `Bearer ${token}`,
            "X-CSRF-TOKEN": csrfToken,
            "Content-Type": "application/json",
        },
        body: JSON.stringify(Array.from(formData.entries())),
    }).then((response) => {
        if(response.ok) {
            showToast({
                title: "Answers submitted",
                description: "Your answers have been submitted successfully",
            });
        } else {
            showToast({
                title: "Answers submission failed",
                description: "There was an error submitting your answers",
            });
        }
    });
}) 