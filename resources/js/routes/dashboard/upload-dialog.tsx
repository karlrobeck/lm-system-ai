import { UploadCloud } from "lucide-solid";
import { Component } from "solid-js";
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
                <form class="space-y-2.5">
                    <Input
                        type="file"
                        name="upload"
                        accept="application/pdf,application/markdown"
                    />
                    <DialogFooter>
                        <Button>
                            <UploadCloud size={16} /> Upload
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    );
};

export default UploadDialog;
