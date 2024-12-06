import { User } from "lucide-solid";
import { Component, createResource, Show } from "solid-js";
import { getUserById } from "~/api/user";
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
    const [user] = createResource(1, async (id: number) => getUserById(id));

    return (
        <Dialog>
            <Show when={user.state === "ready"} fallback={<>Loading...</>}>
                <DialogTrigger
                    id="profile-dialog"
                    class="flex flex-row gap-2.5"
                />
                <DialogContent class="max-w-7xl">
                    <DialogHeader>
                        <DialogDescription>
                            Profile
                        </DialogDescription>
                        <DialogTitle class="flex flex-row items-center gap-2.5">
                            <Avatar>
                                <AvatarFallback>
                                    {user().name.split(" ")
                                        .map((n) => n[0])
                                        .join("")}
                                </AvatarFallback>
                            </Avatar>
                            <span class="heading-3 font-normal">
                                {user().name}
                            </span>
                        </DialogTitle>
                    </DialogHeader>
                    <div class="space-y-5">
                        <div>
                            <div class="border-b border-border">
                                <h4 class="heading-4">
                                    General
                                </h4>
                                <span class="lead small">
                                    General information
                                </span>
                            </div>
                            <Table class="border">
                                <TableBody>
                                    <TableRow>
                                        <TableCell class="muted">
                                            Email
                                        </TableCell>
                                        <TableCell>
                                            {user().email}
                                        </TableCell>
                                    </TableRow>
                                    <TableRow>
                                        <TableCell class="muted">
                                            Level
                                        </TableCell>
                                        <TableCell>
                                            {user().level}
                                        </TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </div>
                        <div class="space-y-5">
                            <div class="border-b border-border">
                                <h4 class="heading-4">
                                    Security
                                </h4>
                                <span class="lead small">
                                    Security information
                                </span>
                            </div>
                            <div class="space-y-2.5">
                                <div class="flex flex-col">
                                    <span class="small font-bold">
                                        Password
                                    </span>
                                    <span class="muted">
                                        Update your password
                                    </span>
                                </div>
                                <div class="grid grid-cols-3 gap-2.5">
                                    <Input
                                        type="password"
                                        placeholder="Old password"
                                    />
                                    <Input
                                        type="password"
                                        placeholder="New password"
                                    />
                                    <Input
                                        type="password"
                                        placeholder="Confirm password"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </DialogContent>
            </Show>
        </Dialog>
    );
};

export default ProfileDialog;
