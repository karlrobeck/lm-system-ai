import { action, redirect } from "@solidjs/router";
import { z } from "zod";

const schema = z.object({
  email: z.string().email(),
  password: z.string(),
});

export const login = action(async (form: FormData) => {
  const csrfToken = document.querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

  //Delay for 1 second to simulate network request
  await new Promise((resolve) => setTimeout(resolve, 1000));
  const data = schema.parse(Object.fromEntries(form.entries()));
  const response = await fetch("/auth/login", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": csrfToken,
    },
    body: JSON.stringify(data),
  });
  if (!response.ok) {
    throw new Error(response.statusText);
  }

  const result = await response.json() as { token: string };

  localStorage.setItem("token", result.token);

  throw redirect("/dashboard");
});
