import {
	type Component,
	createEffect,
	createSignal,
	Show,
	Suspense,
} from "solid-js";
import { render } from "solid-js/web";
import { Navigate, Route, Router, useIsRouting } from "@solidjs/router";
import NotFoundPage from "./routes/not-found";
import DashboardLayout from "./routes/dashboard";
import DashboardPage from "./routes/dashboard/index";
import {
	ColorModeProvider,
	ColorModeScript,
	createLocalStorageManager,
} from "@kobalte/core";

import "@fontsource/inter";
import "../css/app.css";
import ConversationPage from "./routes/dashboard/conversation";
import LoginPage from "./routes/login";
import RegisterPage from "./routes/register";
import { Progress, ProgressValueLabel } from "./components/ui/progress";
import TestPage from "./routes/dashboard/conversation/test";

const MainClient: Component<{}> = (props) => {
	const storageManager = createLocalStorageManager("vite-ui-theme");

	return (
		<Router
			root={(props) => {
				const isRouting = useIsRouting();
				const [progressValue, setProgressValue] = createSignal(0);

				createEffect(() => {
					if (isRouting()) {
						setProgressValue(25);
					} else {
						setProgressValue(100);
						setTimeout(() => {
							setProgressValue(0);
						}, 500);
					}
				});

				return (
					<>
						<ColorModeScript storageType={storageManager.type} />
						<ColorModeProvider storageManager={storageManager}>
							<Suspense>
								<Show when={progressValue()}>
									<Progress
										value={progressValue()}
										minValue={0}
										maxValue={100}
										class="z-50 fixed top-0 left-0 w-full rounded-none transition-all"
									/>
								</Show>
								{props.children}
							</Suspense>
						</ColorModeProvider>
					</>
				);
			}}
		>
			<Route path={"/dashboard"} component={DashboardLayout}>
				<Route path={""} component={DashboardPage} />
				<Route path={"conversation/:id"} component={ConversationPage} />
				<Route path={"test/:mode/:modality/:id/"} component={TestPage} />
			</Route>
			<Route path={""} component={() => <Navigate href={"/dashboard"} />} />
			<Route path={"login"} component={LoginPage} />
			<Route path={"register"} component={RegisterPage} />
			<Route path={"*"} component={NotFoundPage} />
		</Router>
	);
};

const root = document.getElementById("root");

if (!root) {
	throw new Error("Root element not found");
}

render(() => <MainClient />, root);
