import { type Component, createResource, Match, Switch } from "solid-js";
import { getUserById, getUsers } from "~/api/user";

const DashboardPage: Component<{}> = (props) => {
    const [user] = createResource(1, async (id: number) => getUserById(id));

    return (
        <Switch>
            <Match when={user.state === "pending"}>
                <div>Loading...</div>
            </Match>
            <Match when={user.state === "ready"}>
                {JSON.stringify(user())}
            </Match>
        </Switch>
    );
};

export default DashboardPage;
