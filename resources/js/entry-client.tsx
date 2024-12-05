import { Component } from "solid-js";
import { render } from "solid-js/web";
import { Navigate, Route, Router } from "@solidjs/router";
import NotFoundPage from "./routes/not-found";
import DashboardLayout from "./routes/dashboard";
import DashboardPage from "./routes/dashboard/index";

const MainClient: Component<{}> = (props) => {
  return (
    <Router>
      <Route path={"/dashboard"} component={DashboardLayout}>
        <Route path={""} component={DashboardPage} />
      </Route>
      <Route path={""} component={() => <Navigate href={"/dashboard"} />} />
      <Route path={"*"} component={NotFoundPage} />
    </Router>
  );
};

const root = document.getElementById("root")!;

render(() => <MainClient />, root);
