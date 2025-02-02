import {
	A,
	createAsync,
	redirect,
	type RouteSectionProps,
} from "@solidjs/router";
import {
	FileText,
	LoaderCircle,
	LogOut,
	Moon,
	Palette,
	Plus,
	Settings2,
	Sun,
	User,
} from "lucide-solid";
import { createEffect, createResource, For, onMount, Show } from "solid-js";
import { getCurrentUser, getUserById, logout } from "~/api/user";
import { Avatar, AvatarFallback } from "~/components/ui/avatar";
import { Button } from "~/components/ui/button";
import {
	Sidebar,
	SidebarContent,
	SidebarFooter,
	SidebarGroup,
	SidebarGroupLabel,
	SidebarHeader,
	SidebarMenu,
	SidebarMenuButton,
	SidebarMenuItem,
	SidebarProvider,
	SidebarTrigger,
} from "~/components/ui/sidebar";
import {
	Tooltip,
	TooltipContent,
	TooltipTrigger,
} from "~/components/ui/tooltip";
import {
	DropdownMenu,
	DropdownMenuContent,
	DropdownMenuItem,
	DropdownMenuLabel,
	DropdownMenuPortal,
	DropdownMenuSeparator,
	DropdownMenuSub,
	DropdownMenuSubContent,
	DropdownMenuSubTrigger,
	DropdownMenuTrigger,
} from "~/components/ui/dropdown-menu";
import ProfileDialog from "./dashboard/profile-dialog";
import { useColorMode } from "@kobalte/core";
import { Skeleton } from "~/components/ui/skeleton";
import UploadDialog from "./dashboard/upload-dialog";
import AssessmentFormDialog from "./dashboard/assessment-form-dialog";

const DashboardLayout = (props: RouteSectionProps) => {
	if (localStorage.getItem("token") === null) {
		window.location.href = "/login";
	}

	const { setColorMode } = useColorMode();
	const user = createAsync(() => getCurrentUser());

	createEffect(() => {
		if (user().has_assessment === 0) {
			document.getElementById("assessment-form-dialog")?.click();
		}
	});

	return (
		<SidebarProvider>
			<Sidebar class="bg-background">
				<SidebarHeader class="border-b">
					<h4 class="heading-4">P.E.S</h4>
				</SidebarHeader>
				<SidebarContent>
					<SidebarGroup>
						<SidebarGroupLabel>Navigation</SidebarGroupLabel>
						<SidebarMenu>
							<SidebarMenuItem>
								<SidebarMenuButton
									onClick={() => {
										const dialog = document.getElementById(
											"upload-dialog",
										) as HTMLButtonElement;
										dialog.click();
									}}
								>
									<Plus size={16} />
									<p class="truncate">Upload file</p>
								</SidebarMenuButton>
							</SidebarMenuItem>
							<SidebarMenuItem>
								<SidebarMenuButton as={A} href="/dashboard">
									<FileText size={16} />
									<p class="truncate">Scores</p>
								</SidebarMenuButton>
							</SidebarMenuItem>
						</SidebarMenu>
					</SidebarGroup>
					<SidebarGroup>
						<SidebarGroupLabel>Your files</SidebarGroupLabel>
						<SidebarMenu>
							<Show
								when={user() !== undefined}
								fallback={
									<For each={Array.from(Array(25))}>
										{() => <Skeleton height={32} />}
									</For>
								}
							>
								<For each={user().files}>
									{(file) => {
										return (
											<SidebarMenuItem>
												<SidebarMenuButton
													as={"a"}
													href={`/dashboard/conversation/${file.id}`}
												>
													<FileText size={16} />
													<p class="truncate">{file.name}</p>
												</SidebarMenuButton>
											</SidebarMenuItem>
										);
									}}
								</For>
							</Show>
						</SidebarMenu>
					</SidebarGroup>
				</SidebarContent>
				<SidebarFooter class="flex flex-row gap-2.5 items-center justify-between">
					<Avatar>
						<Show
							when={user() !== undefined}
							fallback={<Skeleton height={48} circle />}
						>
							<AvatarFallback>
								{user()
									.name.split(" ")
									.map((n) => n[0])
									.join("")}
							</AvatarFallback>
						</Show>
					</Avatar>
					<Show
						when={user() !== undefined}
						fallback={<Skeleton class="w-full" height={24} radius={10} />}
					>
						<h3 class="font-bold truncate">{user().name}</h3>
					</Show>
					<Show
						when={user() !== undefined}
						fallback={
							<Skeleton
								width={40}
								height={40}
								radius={10}
								class="flex flex-row justify-center items-center"
							>
								<LoaderCircle size={16} class="animate-spin" />
							</Skeleton>
						}
					>
						<DropdownMenu>
							<DropdownMenuTrigger>
								<Tooltip>
									<TooltipTrigger
										as={Button<"button">}
										size="icon"
										variant="ghost"
									>
										<Settings2 size={16} />
									</TooltipTrigger>
									<TooltipContent>Settings</TooltipContent>
								</Tooltip>
							</DropdownMenuTrigger>
							<DropdownMenuContent>
								<DropdownMenuLabel>General</DropdownMenuLabel>
								<DropdownMenuItem
									onClick={() => {
										const dialog = document.getElementById(
											"profile-dialog",
										) as HTMLButtonElement;
										dialog.click();
									}}
								>
									<User size={16} /> Profile
								</DropdownMenuItem>
								<DropdownMenuSeparator />
								<DropdownMenuLabel>Settings</DropdownMenuLabel>
								<DropdownMenuSub overlap>
									<DropdownMenuSubTrigger class="gap-2">
										<Palette size={16} />
										Theme
									</DropdownMenuSubTrigger>
									<DropdownMenuPortal>
										<DropdownMenuSubContent>
											<DropdownMenuItem onSelect={() => setColorMode("light")}>
												<Sun size={16} />
												Light
											</DropdownMenuItem>
											<DropdownMenuItem onSelect={() => setColorMode("dark")}>
												<Moon size={16} />
												Dark
											</DropdownMenuItem>
										</DropdownMenuSubContent>
									</DropdownMenuPortal>
								</DropdownMenuSub>
								<DropdownMenuItem>
									<form action={logout} method="post">
										<button type="submit" class="flex flex-row gap-2.5">
											<LogOut size={16} /> Log out
										</button>
									</form>
								</DropdownMenuItem>
							</DropdownMenuContent>
						</DropdownMenu>
					</Show>
					<ProfileDialog />
					<UploadDialog />
				</SidebarFooter>
			</Sidebar>
			<main class="w-full">
				<header class="border-b border-border w-full p-4">
					<SidebarTrigger />
				</header>
				<ProfileDialog />
				<AssessmentFormDialog />
				<div class="p-4 h-screen">{props.children}</div>
			</main>
		</SidebarProvider>
	);
};

export default DashboardLayout;
