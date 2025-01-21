import { createAsync } from "@solidjs/router";
import { CloudUpload } from "lucide-solid";
import { type Component, createResource, Match, Switch } from "solid-js";
import { getCurrentUser, getUserById, getUsers } from "~/api/user";
import { Button } from "~/components/ui/button";
import { TextField, TextFieldInput } from "~/components/ui/text-field";

const DashboardPage: Component<{}> = (props) => {
	return (
		<article class="pt-24 flex flex-row items-center justify-center">
			<section class="w-1/2 flex flex-col gap-5">
				<h1 class="heading-1">Welcome</h1>
				<Button
					variant="outline"
					onClick={() => {
						document.getElementById("upload-dialog").click();
					}}
				>
					<CloudUpload size={16} />
					Upload a file
				</Button>
			</section>
		</article>
	);
};

export default DashboardPage;
