declare module '@jaxtheprime/vue3-dropzone' {
  import { DefineComponent } from 'vue';

  export interface DropzoneFileData {
    file: File;
    id: string;
    src: string;
    progress: number;
    status: 'pending' | 'uploading' | 'success' | 'error';
    message?: string;
  }

  export interface DropzoneError {
    type: 'file-too-large' | 'invalid-file-format' | 'max-files-exceeded';
    files: File[];
  }

  const Vue3Dropzone: DefineComponent<{
    modelValue?: Array<DropzoneFileData>;
    multiple?: boolean;
    previews?: Array<string>;
    mode?: string;
    disabled?: boolean;
    accept?: string;
    maxFileSize?: number;
    maxFiles?: number;
    width?: string | number;
    height?: string | number;
    imgWidth?: string | number;
    imgHeight?: string | number;
    previewWrapperClasses?: string;
    previewPosition?: 'inside' | 'outside';
    showSelectButton?: boolean;
    selectFileStrategy?: 'replace' | 'merge';
    serverSide?: boolean;
    uploadEndpoint?: string;
    deleteEndpoint?: string;
    headers?: Record<string, string>;
  }>;

  export default Vue3Dropzone;
} 