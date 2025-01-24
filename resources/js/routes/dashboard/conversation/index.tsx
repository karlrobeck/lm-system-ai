import { A, createAsync, revalidate, useParams } from "@solidjs/router";
import { Car, User } from "lucide-solid";
import { type Component, createResource, Show, Suspense } from "solid-js";
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
	const auditory = createAsync(() =>
		modality.auditory.listByContextFile(params.id),
	);
	*/
	const reading = createAsync(
		() => modality.reading.listByContextFile(params.id),
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
					<TabsContent value="preTest">
						<div class="grid grid-cols-3 gap-2.5">
							<Card
								classList={{
									"opacity-50": Boolean(file()?.is_ready) === false,
								}}
							>
								<CardHeader>
									<CardTitle>Reading</CardTitle>
									<CardDescription>
										<Show
											when={file()?.is_ready}
											fallback={<>Test is not ready</>}
										>
											Questions:{" "}
											{reading()?.filter((v) => v.test_type === "pre")
												?.length || 0}
										</Show>
									</CardDescription>
								</CardHeader>
								<CardFooter>
									<Button
										as={A}
										href={`/dashboard/test/pre/reading/${params.id}`}
									>
										Start test
									</Button>
								</CardFooter>
							</Card>
						</div>
					</TabsContent>
					<TabsContent value="postTest">
						<div class="grid grid-cols-3 gap-2.5">
							<Card
								classList={{
									"opacity-50": Boolean(file()?.is_ready) === false,
								}}
							>
								<CardHeader>
									<CardTitle>Reading</CardTitle>
									<CardDescription>
										<Show
											when={file()?.is_ready}
											fallback={<>Test is not ready</>}
										>
											Questions:{" "}
											{reading()?.filter((v) => v.test_type === "post")
												?.length || 0}
										</Show>
									</CardDescription>
								</CardHeader>
								<CardFooter>
									<Button
										as={A}
										href={`/dashboard/test/post/reading/${params.id}`}
									>
										Start test
									</Button>
								</CardFooter>
							</Card>
						</div>
					</TabsContent>
				</Tabs>
			</Suspense>
		</article>
	);
};

export default ConversationPage;
