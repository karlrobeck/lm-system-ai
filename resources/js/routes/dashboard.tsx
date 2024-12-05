import { A, RouteSectionProps } from "@solidjs/router";
import { FileText, LogOut, Settings2, User } from "lucide-solid";
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
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "~/components/ui/dropdown-menu";

const DashboardLayout = (props: RouteSectionProps) => {
    const [user] = createResource(1, async (id: number) => getUserById(id));

    return (
        <SidebarProvider>
            <Show when={user.state === "ready"}>
                <Sidebar>
                    <SidebarHeader>
                        <h3 class="text-xl font-bold">
                            App Title
                        </h3>
                    </SidebarHeader>
                    <SidebarContent>
                        <SidebarGroup>
                            <SidebarGroupLabel>Your files</SidebarGroupLabel>
                            <SidebarMenu>
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
                            </SidebarMenu>
                        </SidebarGroup>
                    </SidebarContent>
                    <SidebarFooter class="flex flex-row gap-2.5 items-center justify-between">
                        <Avatar>
                            <AvatarFallback>
                                {user().name.split(" ")
                                    .map((n) => n[0])
                                    .join("")}
                            </AvatarFallback>
                        </Avatar>
                        <h3 class="font-bold truncate">{user().name}</h3>
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
                                <DropdownMenuLabel>Settings</DropdownMenuLabel>
                                <DropdownMenuItem>
                                    <User size={16} />
                                    Profile
                                </DropdownMenuItem>
                                <DropdownMenuItem>
                                    <LogOut size={16} /> Log out
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </SidebarFooter>
                </Sidebar>
            </Show>
            <main class="w-full">
                <header class="border-b border-border w-full p-4">
                    <SidebarTrigger />
                </header>
                <div class="p-4">
                    {props.children}
                </div>
            </main>
        </SidebarProvider>
    );
};

export default DashboardLayout;
