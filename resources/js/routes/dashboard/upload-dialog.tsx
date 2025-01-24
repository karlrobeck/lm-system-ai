import { UploadCloud } from "lucide-solid";
import type { Component } from "solid-js";
import { uploadFile } from "~/api/file";
import { Button } from "~/components/ui/button";
import {
	Dialog,
	DialogContent,
	DialogDescription,
	DialogFooter,
	DialogHeader,
	DialogTrigger,
} from "~/components/ui/dialog";
import Input from "~/components/ui/input";

const UploadDialog: Component<{}> = (props) => {
	return (
		<Dialog>
			<DialogTrigger id="upload-dialog" class="hidden" />
			<DialogContent>
				<DialogHeader>
					<DialogDescription>Upload file</DialogDescription>
				</DialogHeader>
				<form
					method="post"
					action={uploadFile}
					class="space-y-2.5"
					enctype="multipart/form-data"
				>
					<Input
						type="file"
						name="file"
						accept="application/pdf,application/markdown"
					/>
					<DialogFooter>
						<Button type="submit">
							<UploadCloud size={16} /> Upload
						</Button>
					</DialogFooter>
				</form>
			</DialogContent>
		</Dialog>
	);
};

export default UploadDialog;
