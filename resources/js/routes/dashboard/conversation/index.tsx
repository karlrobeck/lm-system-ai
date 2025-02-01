import { A, createAsync, revalidate, useParams } from "@solidjs/router";
import { Car, Loader2, User } from "lucide-solid";
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
	length,
	link,
}: { title: string; is_ready: boolean; length: number; link: string }) => {
	return (
		<Card
			classList={{
				"opacity-50": is_ready === false,
			}}
		>
			<CardHeader>
				<CardTitle>{title}</CardTitle>
				<CardDescription>
					<Show
						when={is_ready}
						fallback={
							<div class="flex flex-row gap-2.5 items-center">
								<Loader2 size={16} class="animate-spin" />
								<span>Test is not ready</span>
							</div>
						}
					>
						Questions: {length}
					</Show>
				</CardDescription>
			</CardHeader>
			<Show when={is_ready}>
				<CardFooter>
					<Button as={A} href={link}>
						Start test
					</Button>
				</CardFooter>
			</Show>
		</Card>
	);
};

const ConversationPage: Component<{}> = (props) => {
	const params = useParams<{ id: string }>();
	const file = createAsync(
		() => {
			return getFileMetadataById(params.id);
		},
		{
			initialValue: undefined,
		},
	);
	/* 
	const visualization = createAsync(() =>
		modality.visualization.listByContextFile(params.id),
	);
	*/

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
			</Show>
			<Suspense fallback={<Skeleton height={24} class="w-1/4" />}>
				<Tabs>
					<TabsList>
						<TabsTrigger value="preTest">Pre</TabsTrigger>
						<TabsTrigger value="postTest">Post</TabsTrigger>
					</TabsList>
					<Show
						when={
							file() !== undefined &&
							reading() !== undefined &&
							writing() !== undefined &&
							auditory() !== undefined &&
							kinesthetic() !== undefined &&
							assessment() !== undefined
						}
					>
						<TabsContent value="preTest">
							<div class="grid grid-cols-3 gap-2.5">
								<For each={assessment()}>
									{(a) => {
										// get the highest rank that is not failed
										return (
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
													const lowestRank = assessment()
														.filter(
															(v) =>
																Boolean(v.pre_test_passed) === false &&
																Boolean(v.is_failed) === false,
														)
														.reduce((prev, curr) =>
															prev.rank < curr.rank ? prev : curr,
														);
													return (
														<Show when={a.modality === modality}>
															<ModalityCard
																is_ready={lowestRank.rank === a.rank}
																title={
																	modality.charAt(0).toUpperCase() +
																	modality.slice(1)
																}
																length={
																	{
																		reading: reading,
																		writing: writing,
																		auditory: auditory,
																		visualization: () => [],
																		kinesthetic: kinesthetic,
																	}
																		[modality]()
																		?.filter((v) => v.test_type === "pre")
																		?.length || 0
																}
																link={`/dashboard/test/pre/${modality}/${params.id}`}
															/>
														</Show>
													);
												}}
											</For>
										);
									}}
								</For>
							</div>
						</TabsContent>
						<TabsContent value="postTest">
							<div class="grid grid-cols-3 gap-2.5">
								<For each={assessment()}>
									{(a) => {
										// get the highest rank that is not failed
										return (
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
													const lowestRank = assessment()
														.filter(
															(v) =>
																Boolean(v.pre_test_passed) === true &&
																Boolean(v.is_failed) === false,
														)
														.reduce((prev, curr) =>
															prev.rank < curr.rank ? prev : curr,
														);
													return (
														<Show when={a.modality === modality}>
															<ModalityCard
																is_ready={lowestRank.rank === a.rank}
																title={
																	modality.charAt(0).toUpperCase() +
																	modality.slice(1)
																}
																length={
																	{
																		reading: reading,
																		writing: writing,
																		auditory: auditory,
																		visualization: () => [],
																		kinesthetic: kinesthetic,
																	}
																		[modality]()
																		?.filter((v) => v.test_type === "post")
																		?.length || 0
																}
																link={`/dashboard/test/post/${modality}/${params.id}`}
															/>
														</Show>
													);
												}}
											</For>
										);
									}}
								</For>
							</div>
						</TabsContent>
					</Show>
				</Tabs>
			</Suspense>
		</article>
	);
};

export default ConversationPage;
