<template>
  <div class="drop-zone-wrapper" :class="{ 'active': isDragging }" @click.stop>
    <Vue3Dropzone 
      v-model="localModelValue"
      :multiple="multiple"
      :accept="acceptString"
      :max-file-size="maxFileSize"
      :max-files="maxFiles"
      previewPosition="inside"
      :server-side="serverSide"
      class="rounded-xl border-dashed border-2 border-white/20 transition-all duration-300 hover:border-primary/50"
      @error="handleError"
      @fileUploaded="handleFileUploaded"
      @fileRemoved="handleFileRemoved"
      @dragover="isDragging = true"
      @dragleave="isDragging = false"
      @drop="isDragging = false"
    >
      <template #title>
        <span class="text-sm font-medium text-foreground/80">
          Arrastra y suelta tus {{ fileTypeLabel }} o haz click para seleccionar
        </span>
      </template>

      <template #description>
        <span class="text-xs text-foreground/60">
          Supported formats: {{ formattedSupportedFormats }} (Max {{ maxFileSize }}MB)
        </span>
      </template>

      <template #button="{ fileInput }">
        <button 
          type="button"
          @click="fileInput?.click()"
          class="mt-3 text-sm px-4 py-2 bg-primary/20 text-primary-foreground rounded-md hover:bg-primary/30 transition-colors"
        >
          {{ selectButtonText }}
        </button>
      </template>

      <template #placeholder-img>
        <div class="flex items-center justify-center w-full h-full">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-foreground/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
        </div>
      </template>

      <template #preview="{ data, formatSize, removeFile }">
        <div class="relative group">
          <img :src="data.src" class="w-full h-48 object-cover rounded-lg" />
          <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
            <button 
              @click="removeFile(data)" 
              class="p-2 bg-destructive/90 rounded-full text-white"
              type="button"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
            </button>
            <span class="absolute bottom-2 left-2 text-xs bg-black/60 text-white px-2 py-1 rounded">
              {{ formatSize(data.file.size) }}
            </span>
          </div>
        </div>
      </template>
    </Vue3Dropzone>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import Vue3Dropzone, { DropzoneFileData, DropzoneError } from "@jaxtheprime/vue3-dropzone";
import '@jaxtheprime/vue3-dropzone/dist/style.css';

// Define type for acceptType prop
type AcceptType = 'image' | 'pdf';

// Define file type mappings using MIME types
const FILE_TYPE_MAPPINGS: Record<AcceptType, string[]> = {
  image: [
    "image/jpeg", 
    "image/jpg",
    "image/png", 
    "image/gif", 
    "image/svg+xml", 
    "image/webp"
  ],
  pdf: ["application/pdf"],
};

// Define extension mappings for display
const EXTENSION_MAPPINGS: Record<AcceptType, string[]> = {
  image: ["jpg", "jpeg", "png", "gif", "svg", "webp"],
  pdf: ["pdf"],
};

// Define props with defaults
const props = defineProps({
  /**
   * v-model for the file array
   */
  modelValue: {
    type: Array as () => DropzoneFileData[],
    default: () => []
  },
  /**
   * Allow multiple files
   */
  multiple: {
    type: Boolean,
    default: false
  },
  /**
   * File types to accept (can be a custom array of MIME types)
   */
  accept: {
    type: Array as () => string[],
    default: null
  },
  /**
   * Simplified file type selector (image, pdf)
   */
  acceptType: {
    type: String as () => AcceptType,
    default: 'image' as AcceptType,
    validator: (value: string) => ['image', 'pdf'].includes(value)
  },
  /**
   * Maximum file size in MB
   */
  maxFileSize: {
    type: Number,
    default: 5
  },
  /**
   * Maximum number of files
   */
  maxFiles: {
    type: Number,
    default: 5
  },
  /**
   * Text for the select button
   */
  selectButtonText: {
    type: String,
    default: "Select Files"
  },
  /**
   * Label for the file type (image, document, file)
   */
  fileTypeLabel: {
    type: String,
    default: "files"
  },
  /**
   * Supported formats array
   */
  supportedFormats: {
    type: Array as () => string[],
    default: null
  },
  /**
   * Enable server-side uploads
   */
  serverSide: {
    type: Boolean,
    default: false
  }
});

// Define emits
const emit = defineEmits(['update:modelValue', 'error', 'file-uploaded', 'file-removed']);

// Local state
const isDragging = ref(false);
const localModelValue = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value)
});

// Computed property to determine the accept array based on props
const acceptArray = computed(() => {
  if (props.accept) {
    return props.accept;
  }
  return FILE_TYPE_MAPPINGS[props.acceptType];
});

// Convert accept array to comma-separated string for Vue3Dropzone
const acceptString = computed(() => {
  return acceptArray.value.join(',');
});

// Computed property to format the supported formats for display
const formattedSupportedFormats = computed(() => {
  if (props.supportedFormats) {
    return props.supportedFormats.join(", ");
  }
  return EXTENSION_MAPPINGS[props.acceptType].join(", ");
});

// Event handlers
const handleError = (error: DropzoneError) => {
  emit('error', error);
  
  // Default error handling
  const { type, files } = error;
  if (type === 'file-too-large') {
    console.error(`File size exceeds the ${props.maxFileSize}MB limit: ${files.map(f => f.name).join(', ')}`);
  } else if (type === 'invalid-file-format') {
    console.error(`Invalid file format: ${files.map(f => f.name).join(', ')}`);
  } else if (type === 'max-files-exceeded') {
    console.error(`Maximum number of files (${props.maxFiles}) exceeded`);
  }
};

const handleFileUploaded = (fileData: DropzoneFileData) => {
  emit('file-uploaded', fileData);
};

const handleFileRemoved = (fileData: DropzoneFileData) => {
  emit('file-removed', fileData);
};
</script>

<style scoped>
.drop-zone-wrapper {
  transition: all 0.3s ease;
}

.drop-zone-wrapper.active {
  transform: scale(1.02);
  border-color: rgba(var(--primary-rgb), 0.5);
}

/* Add any custom styles for the dropzone here */
</style>
