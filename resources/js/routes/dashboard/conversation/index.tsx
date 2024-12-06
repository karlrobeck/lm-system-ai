import { createAsync, revalidate, useParams } from "@solidjs/router";
import { Car, User } from "lucide-solid";
import { Component, createResource, Show, Suspense } from "solid-js";
import { getFileById } from "~/api/file";
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
        () => getFileById(params.id),
        {
            initialValue: undefined,
        },
    );
    const visualization = createAsync(() =>
        modality.visualization.listByContextFile(params.id)
    );
    const auditory = createAsync(() =>
        modality.auditory.listByContextFile(params.id)
    );
    const readingWriting = createAsync(() =>
        modality.readingWriting.listByContextFile(params.id)
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
                    <BreadcrumbItem>
                        {params.id}
                    </BreadcrumbItem>
                </BreadcrumbList>
            </Breadcrumb>
            <Show
                when={file() !== undefined}
                fallback={<Skeleton height={24} class="w-1/4" />}
            >
                <div class="space-y-2.5">
                    <h4 class="heading-4">{file().name}</h4>
                    <span class="lead small">
                        {file().user.name}
                    </span>
                </div>
            </Show>
            <Show
                when={visualization() !== undefined &&
                    auditory() !== undefined && readingWriting() !== undefined}
                fallback={<Skeleton height={24} class="w-1/4" />}
            >
                <Tabs>
                    <TabsList>
                        <TabsTrigger value="preTest">Pre</TabsTrigger>
                        <TabsTrigger value="postTest">Post</TabsTrigger>
                    </TabsList>
                    <TabsContent value="preTest">
                        <div class="grid grid-cols-3 gap-2.5">
                            <Card>
                                <CardHeader>
                                    <CardTitle>
                                        Visualization Test
                                    </CardTitle>
                                    <CardDescription>
                                        Questions:{" "}
                                        {visualization().filter((v) =>
                                            v.test_type === "pre"
                                        ).length}
                                    </CardDescription>
                                </CardHeader>
                                <CardFooter>
                                    <Button>Start test</Button>
                                </CardFooter>
                            </Card>
                            <Card>
                                <CardHeader>
                                    <CardTitle>
                                        Auditory Test
                                    </CardTitle>
                                    <CardDescription>
                                        Questions: {auditory().filter((v) =>
                                            v.test_type === "pre"
                                        ).length}
                                    </CardDescription>
                                </CardHeader>
                                <CardFooter>
                                    <Button>Start test</Button>
                                </CardFooter>
                            </Card>
                            <Card>
                                <CardHeader>
                                    <CardTitle>
                                        Reading
                                    </CardTitle>
                                    <CardDescription>
                                        Questions:{" "}
                                        {readingWriting().filter((v) =>
                                            v.mode === "reading" &&
                                            v.test_type === "pre"
                                        ).length}
                                    </CardDescription>
                                </CardHeader>
                                <CardFooter>
                                    <Button>Start test</Button>
                                </CardFooter>
                            </Card>
                            <Card>
                                <CardHeader>
                                    <CardTitle>
                                        Writing
                                    </CardTitle>
                                    <CardDescription>
                                        Questions:{" "}
                                        {readingWriting().filter((v) =>
                                            v.mode === "writing" &&
                                            v.test_type === "pre"
                                        ).length}
                                    </CardDescription>
                                </CardHeader>
                                <CardFooter>
                                    <Button>Start test</Button>
                                </CardFooter>
                            </Card>
                        </div>
                    </TabsContent>
                    <TabsContent value="postTest">
                        <div class="grid grid-cols-3 gap-2.5">
                            <Card>
                                <CardHeader>
                                    <CardTitle>
                                        Visualization Test
                                    </CardTitle>
                                    <CardDescription>
                                        Questions:{" "}
                                        {visualization().filter((v) =>
                                            v.test_type === "post"
                                        ).length}
                                    </CardDescription>
                                </CardHeader>
                                <CardFooter>
                                    <Button>Start test</Button>
                                </CardFooter>
                            </Card>
                            <Card>
                                <CardHeader>
                                    <CardTitle>
                                        Auditory Test
                                    </CardTitle>
                                    <CardDescription>
                                        Questions: {auditory().filter((v) =>
                                            v.test_type === "post"
                                        ).length}
                                    </CardDescription>
                                </CardHeader>
                                <CardFooter>
                                    <Button>Start test</Button>
                                </CardFooter>
                            </Card>
                            <Card>
                                <CardHeader>
                                    <CardTitle>
                                        Reading
                                    </CardTitle>
                                    <CardDescription>
                                        Questions:{" "}
                                        {readingWriting().filter((v) =>
                                            v.mode === "reading" &&
                                            v.test_type === "post"
                                        ).length}
                                    </CardDescription>
                                </CardHeader>
                                <CardFooter>
                                    <Button>Start test</Button>
                                </CardFooter>
                            </Card>
                            <Card>
                                <CardHeader>
                                    <CardTitle>
                                        Writing
                                    </CardTitle>
                                    <CardDescription>
                                        Questions:{" "}
                                        {readingWriting().filter((v) =>
                                            v.mode === "writing" &&
                                            v.test_type === "post"
                                        ).length}
                                    </CardDescription>
                                </CardHeader>
                                <CardFooter>
                                    <Button>Start test</Button>
                                </CardFooter>
                            </Card>
                        </div>
                    </TabsContent>
                </Tabs>
            </Show>
        </article>
    );
};

export default ConversationPage;
