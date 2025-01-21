import { action, redirect } from "@solidjs/router";
import { z } from "zod";

const schema = z.object({
  fullName: z.string(),
  email: z.string().email(),
  password: z.string(),
  confirmPassword: z.string(),
  level: z.enum(["primary", "junior", "senior", "tertiary"]),
});

export const register = action(async (form: FormData) => {
  const csrfToken = document.querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

  //Delay for 1 second to simulate network request
  await new Promise((resolve) => setTimeout(resolve, 1000));
  const data = schema.parse(Object.fromEntries(form.entries()));
  console.log(data);

  const response = await fetch("/auth/register", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": csrfToken,
    },
    body: JSON.stringify({
      ...data,
      name: data.fullName,
    }),
  });
  if (!response.ok) {
    throw new Error(response.statusText);
  }
  throw redirect("/login");
});
