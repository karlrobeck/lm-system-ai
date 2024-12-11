import { createAsync } from "@solidjs/router";
import { type Component, createResource, Match, Switch } from "solid-js";
import { getCurrentUser, getUserById, getUsers } from "~/api/user";

const DashboardPage: Component<{}> = (props) => {
    const user = createAsync(() => getCurrentUser());

    return (
        <div>
            {JSON.stringify(user())}
        </div>
    );
};

export default DashboardPage;
