import {
	type Component,
	createResource,
	createSignal,
	For,
	Match,
	Show,
	Switch,
} from "solid-js";
import { getCurrentUser, getRanking } from "~/api/user";
import { Avatar, AvatarFallback } from "~/components/ui/avatar";
import {
	Dialog,
	DialogContent,
	DialogDescription,
	DialogHeader,
	DialogTitle,
	DialogTrigger,
} from "~/components/ui/dialog";
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
	const [ranking] = createResource(() => getRanking());

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
					<div class="space-y-5">
						<div>
							<div class="border-b border-border">
								<h4 class="heading-4">Ranking</h4>
								<span class="lead small">AI Evaluation</span>
							</div>

							<Show when={ranking.state === "ready"}>
								<Table class="border">
									<TableHeader>
										<TableRow>
											<TableHead>Modality</TableHead>
											<TableHead>Rank 1 - 5</TableHead>
											<TableHead>Message</TableHead>
										</TableRow>
									</TableHeader>
									<TableBody>
										<For each={ranking()}>
											{(rank) => (
												<TableRow>
													<TableCell>{rank.modality}</TableCell>
													<TableCell>{rank.rank}</TableCell>
													<TableCell>{rank.message}</TableCell>
												</TableRow>
											)}
										</For>
									</TableBody>
								</Table>
							</Show>
						</div>
					</div>
				</DialogContent>
			</Show>
		</Dialog>
	);
};

export default ProfileDialog;
