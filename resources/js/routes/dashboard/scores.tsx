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
			<div class="grid grid-cols-1 gap-4 py-4">
				<For each={scores()}>
					{(score) => (
						<Card>
							<CardHeader>
								<CardTitle>
									{score.file.name} - {score.modality}
								</CardTitle>
								<CardDescription>
									{score.correct} / {score.total} -{" "}
									{score.is_passed ? "Passed" : "Failed"}
								</CardDescription>
							</CardHeader>
						</Card>
					)}
				</For>
			</div>
		</Show>
	);
};

export default ScoresPage;
