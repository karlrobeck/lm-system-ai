import { Component, Suspense } from "solid-js";
import { render } from "solid-js/web";
import { Navigate, Route, Router } from "@solidjs/router";
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

const MainClient: Component<{}> = (props) => {
  const storageManager = createLocalStorageManager("vite-ui-theme");

  return (
    <Router
      root={(props) => (
        <>
          <ColorModeScript storageType={storageManager.type} />
          <ColorModeProvider storageManager={storageManager}>
            <Suspense>{props.children}</Suspense>
          </ColorModeProvider>
        </>
      )}
    >
      <Route path={"/dashboard"} component={DashboardLayout}>
        <Route path={""} component={DashboardPage} />
        <Route path={"conversation/:id"} component={ConversationPage} />
      </Route>
      <Route path={""} component={() => <Navigate href={"/dashboard"} />} />
      <Route path={"*"} component={NotFoundPage} />
    </Router>
  );
};

const root = document.getElementById("root")!;

render(() => <MainClient />, root);
