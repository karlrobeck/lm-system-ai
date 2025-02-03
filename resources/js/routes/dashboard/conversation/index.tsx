import { A, createAsync, revalidate, useParams } from "@solidjs/router";
import { Car, Check, Loader2, Notebook, User } from "lucide-solid";
import {
	type Component,
	createResource,
	For,
	Match,
	Show,
	Suspense,
	Switch,
} from "solid-js";
import { getFileMetadataById } from "~/api/file";
import { modality } from "~/api/modality";
import { getScores, getScoresByFileId } from "~/api/user";
import {
	Breadcrumb,
	BreadcrumbItem,
	BreadcrumbList,
	BreadcrumbSeparator,
} from "~/components/ui/breadcrumb";
import { Button } from "~/components/ui/button";
import {
	Card,
	CardContent,
	CardDescription,
	CardFooter,
	CardHeader,
	CardTitle,
} from "~/components/ui/card";
import { Skeleton } from "~/components/ui/skeleton";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "~/components/ui/tabs";

const ModalityCard = ({
	title,
	is_ready,
	is_passed,
	length,
	link,
}: {
	title: string;
	is_ready: boolean;
	length: number;
	link: string;
	is_passed: boolean;
}) => {
	return (
		<Card classList={{ "opacity-50": !is_ready }}>
			<CardHeader>
				<CardTitle>{title}</CardTitle>
				<CardDescription>
					<Show when={is_ready} fallback={<>No Questions Avaiable</>}>
						Questions: {length}
					</Show>
				</CardDescription>
			</CardHeader>
			<Show when={is_ready}>
				<CardFooter>
					<Button disabled={!is_ready} as={A} href={link}>
						Start test
					</Button>
				</CardFooter>
			</Show>
		</Card>
	);
};

const ConversationPage: Component<{}> = (props) => {
	const params = useParams<{ id: string }>();
	const scores = createAsync(() => getScoresByFileId(params.id));
	const file = createAsync(
		() => {
			return getFileMetadataById(params.id);
		},
		{
			initialValue: undefined,
		},
	);

	const visualization = createAsync(() =>
		modality.visualization.listByContextFile(params.id),
	);

	const assessment = createAsync(() => modality.assessment.getAllRanking());

	const kinesthetic = createAsync(() =>
		modality.kinesthetic.listByContextFile(params.id),
	);

	const auditory = createAsync(() =>
		modality.auditory.listByContextFile(params.id),
	);

	const reading = createAsync(
		() => modality.reading.listByContextFile(params.id),
		{
			initialValue: undefined,
		},
	);

	const writing = createAsync(
		() => modality.writing.listByContextFile(params.id),
		{
			initialValue: undefined,
		},
	);

	return (
		<article class="space-y-5">
			<Breadcrumb>
				<BreadcrumbList>
					<BreadcrumbItem>
						<a href="/dashboard">Dashboard</a>
					</BreadcrumbItem>
					<BreadcrumbSeparator />
					<BreadcrumbItem>
						<a href="/dashboard/conversations">Conversations</a>
					</BreadcrumbItem>
					<BreadcrumbSeparator />
					<BreadcrumbItem>{params.id}</BreadcrumbItem>
				</BreadcrumbList>
			</Breadcrumb>
			<Show
				when={file() !== undefined}
				fallback={<Skeleton height={24} class="w-1/4" />}
			>
				<div class="space-y-2.5">
					<h4 class="heading-4">{file().name}</h4>
					<span class="lead small">{file().user.name}</span>
				</div>
				<Button variant="outline" as={A} href={`/dashboard/study/${params.id}`}>
					<Notebook size={16} />
					Show Generated Notes
				</Button>
			</Show>
			<Suspense fallback={<Skeleton height={24} class="w-1/4" />}>
				<Tabs>
					<TabsList>
						<TabsTrigger value="preTest">Pre</TabsTrigger>
						<TabsTrigger value="postTest">Post</TabsTrigger>
					</TabsList>
					<TabsContent value="preTest">
						<div class="grid grid-cols-3 gap-2.5">
							<For
								each={[
									"reading",
									"writing",
									"auditory",
									"visualization",
									"kinesthetic",
								]}
							>
								{(modality) => {
									return (
										<ModalityCard
											is_passed={true}
											is_ready={
												{
													reading: reading,
													writing: writing,
													auditory: auditory,
													visualization: visualization,
													kinesthetic: kinesthetic,
												}
													[modality]()
													?.filter((v) => v.test_type === "pre")?.length >= 1
											}
											title={
												modality.charAt(0).toUpperCase() + modality.slice(1)
											}
											length={
												{
													reading: reading,
													writing: writing,
													auditory: auditory,
													visualization: visualization,
													kinesthetic: kinesthetic,
												}
													[modality]()
													?.filter((v) => v.test_type === "pre")?.length || 0
											}
											link={`/dashboard/test/pre/${modality}/${params.id}`}
										/>
									);
								}}
							</For>
						</div>
					</TabsContent>
					<TabsContent value="postTest">
						<div class="grid grid-cols-3 gap-2.5">
							<For
								each={[
									"reading",
									"writing",
									"auditory",
									"visualization",
									"kinesthetic",
								]}
							>
								{(modality) => {
									return (
										<ModalityCard
											is_passed={true}
											is_ready={
												{
													reading: reading,
													writing: writing,
													auditory: auditory,
													visualization: visualization,
													kinesthetic: kinesthetic,
												}
													[modality]()
													?.filter((v) => v.test_type === "post")?.length >= 1
											}
											title={
												modality.charAt(0).toUpperCase() + modality.slice(1)
											}
											length={
												{
													reading: reading,
													writing: writing,
													auditory: auditory,
													visualization: visualization,
													kinesthetic: kinesthetic,
												}
													[modality]()
													?.filter((v) => v.test_type === "post")?.length || 0
											}
											link={`/dashboard/test/post/${modality}/${params.id}`}
										/>
									);
								}}
							</For>
						</div>
					</TabsContent>
				</Tabs>
			</Suspense>
		</article>
	);
};

export default ConversationPage;
