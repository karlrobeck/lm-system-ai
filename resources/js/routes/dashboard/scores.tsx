import { createAsync } from "@solidjs/router";
import { For, Show, type Component } from "solid-js";
import { getCurrentUser, getScores } from "~/api/user";
import {
	Card,
	CardContent,
	CardDescription,
	CardHeader,
	CardTitle,
} from "~/components/ui/card";

const ScoresPage: Component<{}> = (props) => {
	const scores = createAsync(() => getScores());
	const user = createAsync(() => getCurrentUser());

	return (
		<Show when={scores() !== undefined && user() !== undefined}>
			<div class="pb-4">
				<h2 class="heading-2">Scores</h2>
				<span class="muted">
					{user().name} - {user().email}
				</span>
			</div>
			<For each={scores()}>
				{(score) => (
					<Card>
						<CardHeader>
							<CardTitle>
								{score.file.name} - {score.modality}
							</CardTitle>
							<CardDescription>
								{Boolean(score.is_passed) === true ? "Passed" : "Failed"}
							</CardDescription>
						</CardHeader>
					</Card>
				)}
			</For>
		</Show>
	);
};

export default ScoresPage;
