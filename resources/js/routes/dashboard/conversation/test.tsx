import { createAsync, useParams } from "@solidjs/router";
import { Speaker } from "lucide-solid";
import {
	createEffect,
	For,
	Match,
	Show,
	Switch,
	type Component,
} from "solid-js";
import { getFileMetadataById } from "~/api/file";
import {
	type Auditory,
	modality,
	type Reading,
	type Visualization,
	type Writing,
} from "~/api/modality";
import { Button } from "~/components/ui/button";
import {
	RadioGroup,
	RadioGroupItem,
	RadioGroupItemLabel,
} from "~/components/ui/radio-group";
import { TextField, TextFieldTextArea } from "~/components/ui/text-field";

const PreTestPage: Component<{}> = (props) => {
	const params = useParams<{
		mode: "pre" | "post";
		modality:
			| "visualization"
			| "auditory"
			| "reading"
			| "writing"
			| "kinesthetic";
		id: string;
	}>();

	const file = createAsync(() => getFileMetadataById(params.id));

	const kinesthetic = createAsync(() => {
		if (params.modality === "kinesthetic") {
			return modality.kinesthetic.listByContextFile(params.id);
		}
		return undefined;
	});

	const auditory = createAsync(() => {
		if (params.modality === "auditory") {
			return modality.auditory.listByContextFile(params.id);
		}
		return undefined;
	});

	const reading = createAsync(() => {
		if (params.modality === "reading") {
			return modality.reading.listByContextFile(params.id);
		}
		return undefined;
	});

	const writing = createAsync(() => {
		if (params.modality === "writing") {
			return modality.writing.listByContextFile(params.id);
		}
		return undefined;
	});

	return (
		<article class="space-y-5">
			<div>
				<Show when={file() !== undefined}>
					<p class="muted">{file().name}</p>
				</Show>
				<h2 class="heading-2 first-letter:uppercase border-b">
					{params.modality}
				</h2>
			</div>
			<Show when={reading() !== undefined}>
				<form class="space-y-5">
					<div class="space-y-5">
						<For each={reading().filter((q) => q.test_type === params.mode)}>
							{(question) => (
								<div class="border-b">
									<div>
										<span class="muted">{question.question_index}.</span>{" "}
										{question.question}
									</div>
									<RadioGroup
										name={`${question.question_index}`}
										class="flex flex-col gap-2.5 p-4"
									>
										<For each={JSON.parse(question.choices)}>
											{(choice) => (
												<RadioGroupItem value={choice}>
													<RadioGroupItemLabel>{choice}</RadioGroupItemLabel>
												</RadioGroupItem>
											)}
										</For>
									</RadioGroup>
								</div>
							)}
						</For>
					</div>
					<Button>Submit Answer</Button>
				</form>
			</Show>
			<Show when={writing() !== undefined}>
				<form>
					<For each={writing().filter((q) => q.test_type === params.mode)}>
						{(question) => (
							<div>
								<div>
									<span class="muted">{question.question_index}.</span>{" "}
									{question.question}
								</div>
								<TextField class="m-4">
									<TextFieldTextArea
										name={`${question.question_index}`}
										placeholder="Your answer"
									/>
								</TextField>
							</div>
						)}
					</For>
					<Button>Submit Answer</Button>
				</form>
			</Show>
			<Show when={auditory() !== undefined}>
				<form>
					<For each={auditory().filter((q) => q.test_type === params.mode)}>
						{(question) => (
							<div>
								<div>
									<span class="muted">{question.question_index}.</span>{" "}
									<Button
										class="w-1/2 "
										size="sm"
										variant="outline"
										onClick={() => {
											const utterance = new SpeechSynthesisUtterance(
												question.question,
											);
											utterance.voice = speechSynthesis
												.getVoices()
												.find(
													(voice) => voice.name === "Google UK English Male",
												);
											speechSynthesis.speak(utterance);
										}}
									>
										Speak <Speaker size={16} />
									</Button>
								</div>
								<RadioGroup
									name={`${question.question_index}`}
									class="flex flex-col gap-2.5 p-4"
								>
									<For each={JSON.parse(question.choices)}>
										{(choice) => (
											<RadioGroupItem value={choice}>
												<RadioGroupItemLabel>{choice}</RadioGroupItemLabel>
											</RadioGroupItem>
										)}
									</For>
								</RadioGroup>
							</div>
						)}
					</For>
					<Button>Submit Answer</Button>
				</form>
			</Show>
			<Show when={kinesthetic() !== undefined}>
				<form>
					<For each={kinesthetic().filter((q) => q.test_type === params.mode)}>
						{(question) => (
							<div>
								<div>
									<span class="muted">{question.question_index}.</span>{" "}
									{question.question}
								</div>
								<TextField class="m-4">
									<TextFieldTextArea
										name={`${question.question_index}`}
										placeholder="Your answer"
									/>
								</TextField>
							</div>
						)}
					</For>
					<Button>Submit Answer</Button>
				</form>
			</Show>
		</article>
	);
};

export default PreTestPage;
