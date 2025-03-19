import { action, createAsync } from "@solidjs/router";
import { For, Show, type Component } from "solid-js";
import { getCurrentUser } from "~/api/user";
import { Button } from "~/components/ui/button";
import { Checkbox } from "~/components/ui/checkbox";
import {
	Dialog,
	DialogContent,
	DialogDescription,
	DialogHeader,
	DialogTitle,
	DialogTrigger,
} from "~/components/ui/dialog";
import { TextField } from "~/components/ui/text-field";

const FORM_QUESTIONS = [
	{
		question: "When choosing my subjects to study, these are important for me:",
		options: [
			{
				type: "kinesthetic",
				value: "Applying my knowledge in real situations.",
			},
			{
				type: "reading/writing",
				value: "Using words well in written communications.",
			},
			{
				type: "auditory",
				value: "Communicating with others through discussion.",
			},
			{
				type: "visualization",
				value: "Working with designs, maps or charts.",
			},
		],
	},
	{
		question:
			"I want to find out about a house or an apartment. Before visiting it, I would want:",
		options: [
			{
				type: "visualization",
				value: "a plan showing the rooms and a map of the area.",
			},
			{
				type: "kinesthetic",
				value: "to view a video of the property.",
			},
			{
				type: "reading/writing",
				value: "a printed description of the rooms and features.",
			},
			{
				type: "auditory",
				value: "a discussion with the owner.",
			},
		],
	},
	{
		question:
			"I want to suggest fund-raising options for a sports team. I would:",
		options: [
			{
				type: "auditory",
				value: "question others who have been involved with fundraising.",
			},
			{
				type: "reading/writing",
				value: "list details about different options.",
			},
			{
				type: "visualization",
				value: "compare graphs of different fund-raising options.",
			},
			{
				type: "kinesthetic",
				value: "focus on fund-raising options that I know will work.",
			},
		],
	},
	{
		question:
			"I want to find out more about a tour that I am going on. I would:",
		options: [
			{
				type: "kinesthetic",
				value: "watch videos to see if there are things I like.",
			},
			{
				type: "visualization",
				value: "use a map and see where the places are.",
			},
			{
				type: "auditory",
				value:
					"talk with the person who planned the tour or others who are going on the tour.",
			},
			{
				type: "reading/writing",
				value: "read about the tour on the itinerary.",
			},
		],
	},
	{
		question: "I want to learn how to take better photos. I would:",
		options: [
			{
				type: "auditory",
				value:
					"ask questions and talk about how to achieve interesting effects.",
			},
			{
				type: "reading/writing",
				value: "use the written instructions about what to do.",
			},
			{
				type: "visualization",
				value: "use diagrams showing how different camera settings work.",
			},
			{
				type: "kinesthetic",
				value:
					"use examples of good and poor photos showing how to improve them.",
			},
		],
	},
	{
		question: "When I am learning I:",
		options: [
			{
				type: "kinesthetic",
				value: "use examples and applications.",
			},
			{
				type: "auditory",
				value: "like to talk things through.",
			},
			{
				type: "visualization",
				value: "see patterns in things.",
			},
			{
				type: "reading/writing",
				value: "read books, articles and handouts.",
			},
		],
	},
	{
		question:
			"I want to assemble a wooden table that came in parts (kitset). I would learn best from:",
		options: [
			{
				type: "auditory",
				value: "advice from someone who has done it before.",
			},
			{
				type: "visualization",
				value: "diagrams showing each stage of the assembly.",
			},
			{
				type: "reading/writing",
				value: "written instructions that came with the parts for the table.",
			},
			{
				type: "kinesthetic",
				value: "watching a video of a person assembling a similar table.",
			},
		],
	},
	{
		question:
			"I need to find the way to a shop that a friend has recommended. I would:",
		options: [
			{
				type: "auditory",
				value: "ask my friend to tell me the directions.",
			},
			{
				type: "visualization",
				value: "use a map.",
			},
			{
				type: "reading/writing",
				value: "write down the street directions I need to remember.",
			},
			{
				type: "kinesthetic",
				value: "find out where the shop is in relation to somewhere I know.",
			},
		],
	},
	{
		question: "I have a problem with my knee. I would prefer that the doctor:",
		options: [
			{
				type: "kinesthetic",
				value: "used a plastic model to show me what was wrong.",
			},
			{
				type: "auditory",
				value: "described what was wrong.",
			},
			{
				type: "reading/writing",
				value: "gave me something to read to explain what was wrong.",
			},
			{
				type: "visualization",
				value: "showed me a diagram of what was wrong.",
			},
		],
	},
	{
		question:
			"After reading a play, I need to do a project. I would prefer to:",
		options: [
			{
				type: "kinesthetic",
				value: "act out a scene from the play.",
			},
			{
				type: "auditory",
				value: "read a speech from the play.",
			},
			{
				type: "visualization",
				value: "draw or sketch a scene from the play.",
			},
			{
				type: "reading/writing",
				value: "write about the play.",
			},
		],
	},
	{
		question:
			"I want to learn how to play a new board game or card game. I would:",
		options: [
			{
				type: "visualization",
				value:
					"use the diagrams that explain the various stages, moves and strategies in the game.",
			},
			{
				type: "kinesthetic",
				value: "watch others play the game before joining in.",
			},
			{
				type: "reading/writing",
				value: "read the instructions.",
			},
			{
				type: "auditory",
				value: "listen to somebody explaining it and ask questions.",
			},
		],
	},
	{
		question:
			"I have finished a competition or test and I would like some feedback. I would like to have feedback:",
		options: [
			{
				type: "reading/writing",
				value: "using a written description of my results.",
			},
			{
				type: "kinesthetic",
				value: "using examples from what I have done.",
			},
			{
				type: "visualization",
				value: "using graphs of my results.",
			},
			{
				type: "auditory",
				value: "from somebody who talks it through with me.",
			},
		],
	},
	{
		question: "When learning from the Internet I like:",
		options: [
			{
				type: "auditory",
				value: "audio channels where I can listen to podcasts or interviews.",
			},
			{
				type: "kinesthetic",
				value: "videos showing how to do or make things.",
			},
			{
				type: "visualization",
				value: "interesting design and visual features.",
			},
			{
				type: "reading/writing",
				value: "interesting written descriptions, lists and explanations.",
			},
		],
	},
	{
		question: "I want to learn to do something new on a computer. I would:",
		options: [
			{
				type: "visualization",
				value: "follow the diagrams in a manual or online.",
			},
			{
				type: "kinesthetic",
				value: "start using it and learn by trial and error.",
			},
			{
				type: "auditory",
				value: "talk with people who know about the program.",
			},
			{
				type: "reading/writing",
				value: "read the written instructions that came with the program.",
			},
		],
	},
	{
		question:
			"A website has a video showing how to make a special graph or chart. There is a person speaking, some lists and words describing what to do and some diagrams. I would learn most from:",
		options: [
			{
				type: "auditory",
				value: "listening.",
			},
			{
				type: "visualization",
				value: "seeing the diagrams.",
			},
			{
				type: "kinesthetic",
				value: "watching the actions.",
			},
			{
				type: "reading/writing",
				value: "reading the words.",
			},
		],
	},
];

const FormQuestion: Component<{
	question_index: number;
	question: string;
	options: {
		type: string;
		value: string;
	}[];
}> = (props) => {
	return (
		<div>
			<div class="flex items-center gap-2">
				<span class="muted">{props.question_index}.</span>
				<p class="large">{props.question}</p>
			</div>
			<div class="p-4 flex flex-col gap-5">
				<For each={props.options}>
					{(option) => (
						<div class="flex items-center gap-2">
							<Checkbox
								name={`assessment-question-${option.type}-${props.question_index}`}
								value={option.value}
							/>
							<p class="small">{option.value}</p>
						</div>
					)}
				</For>
			</div>
		</div>
	);
};

const assessmentFormAction = action(async (formData: FormData) => {
	const token = localStorage.getItem("token");
	const csrfToken = document
		.querySelector('meta[name="csrf-token"]')
		.getAttribute("content");
	const userResponse = Array.from(formData.entries());

	const response = await fetch("/api/assessment/submit", {
		method: "POST",
		headers: {
			Authorization: `Bearer ${token}`,
			"X-CSRF-TOKEN": csrfToken,
			"Content-Type": "application/json",
		},
		body: JSON.stringify(userResponse),
	});

	console.log(response.status);
});

const AssessmentFormDialog: Component<{}> = (props) => {
	const user = createAsync(() => getCurrentUser());

	return (
		<Show when={user() !== undefined}>
			<Dialog open={Boolean(user().has_assessment) === false}>
				<DialogTrigger id="assessment-form-dialog" class="hidden" />
				<DialogContent class="max-w-7xl">
					<DialogHeader>
						<DialogTitle>First time user assessment</DialogTitle>
						<DialogDescription>
							Choose the answer which best explains your preference and click
							the box next to it. Please click more than one if a single answer
							does not match your perception. Leave blank any question that does
							not apply.
						</DialogDescription>
					</DialogHeader>
					<form
						method="post"
						action={assessmentFormAction}
						enctype="multipart/form-data"
					>
						<For each={FORM_QUESTIONS}>
							{(question, index) => (
								<FormQuestion
									question_index={index() + 1}
									question={question.question}
									options={question.options}
								/>
							)}
						</For>
						<Button
							type="submit"
							onClick={() =>
								document.getElementById("assessment-form-dialog").click()
							}
						>
							Submit
						</Button>
					</form>
				</DialogContent>
			</Dialog>
		</Show>
	);
};

export default AssessmentFormDialog;
