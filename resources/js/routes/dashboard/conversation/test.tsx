import { createAsync, useParams } from "@solidjs/router";
import {
	createEffect,
	For,
	Match,
	Show,
	Switch,
	type Component,
} from "solid-js";
import {
	type Auditory,
	modality,
	type ReadingWriting,
	type Visualization,
} from "~/api/modality";

const PreTestPage: Component<{}> = (props) => {
	const params = useParams<{
		mode: "pre" | "post";
		modality: "visualization" | "auditory" | "reading-writing";
		id: string;
		readingWritingType?: "reading" | "writing";
	}>();

	const test = createAsync<Visualization[] | Auditory[] | ReadingWriting[]>(
		() => {
			switch (params.modality) {
				case "visualization":
					return modality.visualization.listByContextFile(params.id);
				case "auditory":
					return modality.auditory.listByContextFile(params.id);
				case "reading-writing":
					return modality.readingWriting.listByContextFile(params.id);
				default:
					throw new Error("Invalid modality");
			}
		},
	);

	return (
		<Switch>
			<Match
				when={
					params.modality === "reading-writing" &&
					params.readingWritingType === "reading"
				}
			>
				<For each={test()} fallback={<div>Loading...</div>}>
					{(item: ReadingWriting, index) => {
						if (params.mode === item.test_type && item.mode === "reading") {
							return (
								<div>
									<h4 class="lead">Question: {index()}</h4>
									<p class="paragraph large">{item.question}</p>
									<ul class="list-disc py-4">
										<For each={JSON.parse(item.choices)}>
											{(choice) => <li class="ml-8">{choice}</li>}
										</For>
									</ul>
								</div>
							);
						}
					}}
				</For>
			</Match>
			<Match
				when={
					params.modality === "reading-writing" &&
					params.readingWritingType === "writing"
				}
			>
				<For each={test()} fallback={<div>Loading...</div>}>
					{(item: ReadingWriting, index) => {
						if (params.mode === item.test_type) {
							return (
								<div>
									<h1 class="lead">Question: {index() + 1}</h1>
									<p class="paragraph large">{item.question}</p>
								</div>
							);
						}
					}}
				</For>
			</Match>
			<Match when={params.modality === "visualization"}>
				<For each={test()} fallback={<div>Loading...</div>}>
					{(item: Visualization, index) => {
						if (params.mode === item.test_type) {
							return (
								<div>
									<h1 class="lead">Question: {index() + 1}</h1>
									<img src={item.image_file.path} alt="Not Available" />
									<p class="paragraph large">{item.question}</p>
									<ul class="list-disc py-4">
										<For each={JSON.parse(item.choices)}>
											{(choice) => <li class="ml-8">{choice}</li>}
										</For>
									</ul>
								</div>
							);
						}
					}}
				</For>
			</Match>
		</Switch>
	);
};

export default PreTestPage;
