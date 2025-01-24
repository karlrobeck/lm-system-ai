import { User } from "lucide-solid";
import {
	type Component,
	createResource,
	createSignal,
	Match,
	Show,
	Switch,
} from "solid-js";
import { getCurrentUser, getUserById } from "~/api/user";
import { Avatar, AvatarFallback } from "~/components/ui/avatar";
import { Button } from "~/components/ui/button";
import {
	Dialog,
	DialogContent,
	DialogDescription,
	DialogHeader,
	DialogTitle,
	DialogTrigger,
} from "~/components/ui/dialog";
import Input from "~/components/ui/input";
import {
	Table,
	TableBody,
	TableCell,
	TableHead,
	TableHeader,
	TableRow,
} from "~/components/ui/table";

const ProfileDialog: Component<{}> = (props) => {
	const [user] = createResource(() => getCurrentUser());
	const [updatePassword, setUpdatePassword] = createSignal(true);

	return (
		<Dialog>
			<Show when={user.state === "ready"}>
				<DialogTrigger id="profile-dialog" class="hidden" />
				<DialogContent class="max-w-7xl">
					<DialogHeader>
						<DialogDescription>Profile</DialogDescription>
						<DialogTitle class="flex flex-row items-center gap-2.5">
							<Avatar>
								<AvatarFallback>
									{user()
										.name.split(" ")
										.map((n) => n[0])
										.join("")}
								</AvatarFallback>
							</Avatar>
							<span class="heading-3 font-normal">{user().name}</span>
						</DialogTitle>
					</DialogHeader>
					<div class="space-y-5">
						<div>
							<div class="border-b border-border">
								<h4 class="heading-4">General</h4>
								<span class="lead small">General information</span>
							</div>
							<Table class="border">
								<TableBody>
									<TableRow>
										<TableCell class="muted">Email</TableCell>
										<TableCell>{user().email}</TableCell>
									</TableRow>
									<TableRow>
										<TableCell class="muted">Level</TableCell>
										<TableCell>{user().level}</TableCell>
									</TableRow>
								</TableBody>
							</Table>
						</div>
					</div>
				</DialogContent>
			</Show>
		</Dialog>
	);
};

export default ProfileDialog;
