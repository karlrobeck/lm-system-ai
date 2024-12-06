import { A, RouteSectionProps } from "@solidjs/router";
import {
    FileText,
    LoaderCircle,
    LogOut,
    Moon,
    Palette,
    Settings2,
    Sun,
    User,
} from "lucide-solid";
import { createResource, For, Show } from "solid-js";
import { getUserById } from "~/api/user";
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
import ProfileDialog from "./dashboard/profile";
import { useColorMode } from "@kobalte/core";
import { Skeleton } from "~/components/ui/skeleton";

const DashboardLayout = (props: RouteSectionProps) => {
    const { setColorMode } = useColorMode();
    const [user] = createResource(1, async (id: number) => getUserById(id));

    return (
        <SidebarProvider>
            <Sidebar>
                <SidebarHeader>
                    <h4 class="heading-4">
                        App Title
                    </h4>
                </SidebarHeader>
                <SidebarContent>
                    <SidebarGroup>
                        <SidebarGroupLabel>Your files</SidebarGroupLabel>
                        <SidebarMenu>
                            <Show
                                when={user.state === "ready"}
                                fallback={
                                    <For each={Array.from(Array(25))}>
                                        {() => <Skeleton height={32} />}
                                    </For>
                                }
                            >
                                <For each={user().files}>
                                    {(file) => (
                                        <SidebarMenuItem>
                                            <SidebarMenuButton
                                                as={A}
                                                href={`/dashboard/files/${file.id}`}
                                            >
                                                <FileText size={16} />
                                                <p class="truncate">
                                                    {file.name}
                                                </p>
                                            </SidebarMenuButton>
                                        </SidebarMenuItem>
                                    )}
                                </For>
                            </Show>
                        </SidebarMenu>
                    </SidebarGroup>
                </SidebarContent>
                <SidebarFooter class="flex flex-row gap-2.5 items-center justify-between">
                    <Avatar>
                        <Show
                            when={user.state === "ready"}
                            fallback={<Skeleton height={48} circle />}
                        >
                            <AvatarFallback>
                                {user().name.split(" ")
                                    .map((n) => n[0])
                                    .join("")}
                            </AvatarFallback>
                        </Show>
                    </Avatar>
                    <Show
                        when={user.state === "ready"}
                        fallback={
                            <Skeleton
                                class="w-full"
                                height={24}
                                radius={10}
                            />
                        }
                    >
                        <h3 class="font-bold truncate">{user().name}</h3>
                    </Show>
                    <Show
                        when={user.state === "ready"}
                        fallback={
                            <Skeleton
                                width={40}
                                height={40}
                                radius={10}
                                class="flex flex-row justify-center items-center"
                            >
                                <LoaderCircle
                                    size={16}
                                    class="animate-spin"
                                />
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
                                    <TooltipContent>
                                        Settings
                                    </TooltipContent>
                                </Tooltip>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent>
                                <DropdownMenuLabel>
                                    General
                                </DropdownMenuLabel>
                                <DropdownMenuItem
                                    onClick={() => {
                                        const dialog = document
                                            .getElementById(
                                                "profile-dialog",
                                            )! as HTMLButtonElement;
                                        dialog.click();
                                    }}
                                >
                                    <User size={16} /> Profile
                                </DropdownMenuItem>
                                <DropdownMenuSeparator />
                                <DropdownMenuSub overlap>
                                    <DropdownMenuSubTrigger class="gap-2">
                                        <Palette size={16} />Theme
                                    </DropdownMenuSubTrigger>
                                    <DropdownMenuPortal>
                                        <DropdownMenuSubContent>
                                            <DropdownMenuItem
                                                onSelect={() =>
                                                    setColorMode("light")}
                                            >
                                                <Sun size={16} />
                                                Light
                                            </DropdownMenuItem>
                                            <DropdownMenuItem
                                                onSelect={() =>
                                                    setColorMode("dark")}
                                            >
                                                <Moon size={16} />
                                                Dark
                                            </DropdownMenuItem>
                                        </DropdownMenuSubContent>
                                    </DropdownMenuPortal>
                                </DropdownMenuSub>
                                <DropdownMenuItem>
                                    <LogOut size={16} /> Log out
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </Show>
                    <ProfileDialog />
                </SidebarFooter>
            </Sidebar>
            <main class="w-full">
                <header class="border-b border-border w-full p-4">
                    <SidebarTrigger />
                </header>
                <ProfileDialog />
                <div class="p-4">
                    {props.children}
                </div>
            </main>
        </SidebarProvider>
    );
};

export default DashboardLayout;
