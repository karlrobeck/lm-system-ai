import { A, useSubmission } from "@solidjs/router";
import { Component, createEffect, Show } from "solid-js";
import { Button } from "~/components/ui/button";
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from "~/components/ui/card";
import Input from "~/components/ui/input";
import { login } from "./action";
import { LoaderCircle } from "lucide-solid";

const LoginPage: Component<{}> = (props) => {
  if (localStorage.getItem("token") !== null) {
    window.location.href = "/dashboard";
  }

  const loginSubmission = useSubmission(login);

  createEffect(() => {
    if (loginSubmission.error) {
      console.error(loginSubmission.error);
    }
    if (loginSubmission.result !== undefined) {
      console.log(loginSubmission.result);
    }
  });

  return (
    <main class="flex flex-col justify-center items-center h-screen">
      <Card class="w-full max-w-3xl">
        <CardHeader>
          <CardTitle>Login</CardTitle>
          <CardDescription>
            Welcome back! Please login to your account.
          </CardDescription>
        </CardHeader>
        <form action={login} method="post">
          <CardContent class="flex flex-col gap-2.5">
            <Input name="email" type="email" placeholder="Email" />
            <Input name="password" type="password" placeholder="Password" />
          </CardContent>
          <CardFooter class="gap-2.5">
            <Button disabled={loginSubmission.pending} type="submit">
              <Show
                when={!loginSubmission.pending}
                fallback={
                  <>
                    <LoaderCircle class="animate-spin" size={16} />
                    Loading
                  </>
                }
              >
                Login
              </Show>
            </Button>
            <Button as={A} href="/register" type="button" variant="link">
              Register
            </Button>
          </CardFooter>
        </form>
      </Card>
    </main>
  );
};

export default LoginPage;
