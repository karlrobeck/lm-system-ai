import { createAsync, useParams } from "@solidjs/router";
import { Loader2, NotebookPen } from "lucide-solid";
import { Show, type Component } from "solid-js";
import { getFileMetadataById } from "~/api/file";
import showdown from "showdown";

const StudyNotesPage: Component<{}> = (props) => {
	const params = useParams<{ id: string }>();
	const converter = new showdown.Converter();

	const file = createAsync(() => getFileMetadataById(params.id));

	return (
		<Show when={file() !== undefined}>
			<div class="h-full">
				<div class="border-b pb-4">
					<h2 class="heading-2">{file().name} - Study notes</h2>
					<div class="flex flex-col gap-2.5">
						<span class="muted">{file().user.email}</span>
					</div>
				</div>
				<div class="p-4 h-full">
					<Show
						when={true}
						fallback={
							<div class="flex flex-row justify-center items-center h-full gap-2.5">
								<NotebookPen size={24} />
								<span class="large">Woops, no study notes available</span>
							</div>
						}
					>
						<article
							class="prose prose-neutral prose-invert !max-w-none"
							innerHTML={converter.makeHtml(file().study_notes)}
						/>
					</Show>
				</div>
			</div>
		</Show>
	);
};

export default StudyNotesPage;
