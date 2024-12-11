import { A, useSubmission } from "@solidjs/router";
import { Component, createEffect, createSignal, Show } from "solid-js";
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
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "~/components/ui/select";
import { register } from "./action";
import { LoaderCircle } from "lucide-solid";

const RegisterPage: Component<{}> = (props) => {
  if (localStorage.getItem("token") !== null) {
    window.location.href = "/dashboard";
  }

  const [level, setLevel] = createSignal<
    "primary" | "junior" | "senior" | "tertiary" | ""
  >("");
  const registerSubmission = useSubmission(register);

  createEffect(() => {
    if (registerSubmission.error) {
      console.error(registerSubmission.error);
    }
  });

  return (
    <main class="flex flex-col justify-center items-center h-screen">
      <Card class="w-full max-w-3xl">
        <CardHeader>
          <CardTitle>Register</CardTitle>
          <CardDescription>
            Welcome! Please register for a new account.
          </CardDescription>
        </CardHeader>
        <form action={register} method="post">
          <CardContent class="flex flex-col gap-2.5">
            <Input
              required
              name="fullName"
              type="text"
              placeholder="Full name"
            />
            <Input required name="email" type="email" placeholder="Email" />
            <div class="grid grid-cols-2 gap-2.5">
              <Input
                required
                name="password"
                type="password"
                placeholder="Password"
              />
              <Input
                required
                name="confirmPassword"
                type="password"
                placeholder="Confirm password"
              />
            </div>
            <Select
              value={level()}
              onChange={setLevel}
              options={["primary", "junior", "senior", "tertiary"]}
              placeholder="Select your level"
              required
              name="level"
              itemComponent={(props) => (
                <SelectItem item={props.item}>
                  {props.item.textValue}
                </SelectItem>
              )}
            >
              <SelectTrigger aria-label="Level">
                <SelectValue<string>>
                  {(state) => state.selectedOption()}
                </SelectValue>
              </SelectTrigger>
              <SelectContent />
            </Select>
            {level() && (
              <input value={level()} hidden name="level" type="text" />
            )}
          </CardContent>
          <CardFooter class="gap-2.5">
            <Button type="submit" disabled={registerSubmission.pending}>
              <Show
                when={!registerSubmission.pending}
                fallback={
                  <>
                    <LoaderCircle class="animate-spin" size={16} />
                    Loading
                  </>
                }
              >
                Register
              </Show>
            </Button>
            <Button as={A} href="/login" type="button" variant="link">
              Login
            </Button>
          </CardFooter>
        </form>
      </Card>
    </main>
  );
};

export default RegisterPage;
