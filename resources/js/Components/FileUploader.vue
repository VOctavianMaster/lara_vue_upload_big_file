<template>
    <div class="upload-container">
        <input
            type="file"
            id="file-upload"
            @change="handleFileChange"
            ref="fileInput"
            class="file-input"
        />
        <label for="file-upload" class="custom-file-upload">
            Choose File
        </label>

        <div v-if="file" class="file-info">
            <p>File: <strong>{{ file.name }}</strong></p>
            <p v-if="uploadProgress > 0">Progress: <strong>{{ uploadProgress }}%</strong></p>
        </div>

        <div v-if="uploadProgress > 0" class="progress-container">
            <div class="progress-bar" :style="{ width: uploadProgress + '%' }"></div>
        </div>

        <div v-if="uploadProgress === 100" class="upload-complete">
            <p>Upload complete!</p>
        </div>

        <div v-if="retrying" class="retrying">
            <p>Retrying... ({{ retryCount }} of {{ maxRetries }})</p>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            file: null,
            uploadProgress: 0,
            retryCount: 0,
            maxRetries: 2,
            retrying: false,
            timestamp: null, // Store the timestamp when upload starts
        };
    },
    methods: {
        handleFileChange(event) {
            this.file = event.target.files[0];
            if (this.file) {
                this.timestamp = Date.now(); // Get current timestamp when upload starts
                this.uploadFile(this.file);
            }
        },
        async uploadFile(file) {
            const chunkSize = 5 * 1024 * 1024; // 5 MB per chunk
            const totalChunks = Math.ceil(file.size / chunkSize);
            let currentChunk = 0;

            // Retry logic
            const uploadChunkWithRetry = async (chunk, chunkIndex) => {
                const formData = new FormData();
                formData.append("file", chunk);
                formData.append("filename", file.name);
                formData.append("chunkIndex", chunkIndex);
                formData.append("totalChunks", totalChunks);
                formData.append("timestamp", this.timestamp); // Add timestamp to each chunk upload

                try {
                    this.retrying = false;  // Reset retrying status
                    await axios.post("/api/upload-chunk", formData, {
                        headers: { "Content-Type": "multipart/form-data" },
                    });
                    this.uploadProgress = Math.round(((chunkIndex + 1) / totalChunks) * 100);
                    currentChunk++;
                } catch (error) {
                    if (this.retryCount < this.maxRetries && this.isNetworkError(error)) {
                        this.retryCount++;
                        this.retrying = true;
                        setTimeout(() => this.uploadChunkWithRetry(chunk, chunkIndex), 3000); // Retry after 3 seconds
                    } else {
                        console.error("Upload failed after retries:", error);
                        this.retrying = false;
                    }
                }
            };

            while (currentChunk < totalChunks) {
                const start = currentChunk * chunkSize;
                const end = Math.min(file.size, start + chunkSize);
                const chunk = file.slice(start, end);

                await uploadChunkWithRetry(chunk, currentChunk);
            }

            if (this.uploadProgress === 100) {
                console.log("File uploaded successfully!");
            }
        },

        isNetworkError(error) {
            // Check if the error is due to a network issue (including no internet connection)
            return error.message === 'Network Error' || error.code === 'ERR_INTERNET_DISCONNECTED';
        },
    },
};
</script>

<style scoped>
.upload-container {
    max-width: 400px;
    margin: 0 auto;
    text-align: center;
}

.file-input {
    display: none; /* Hide the default file input */
}

.custom-file-upload {
    display: inline-block;
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 4px;
    font-size: 16px;
}

.custom-file-upload:hover {
    background-color: #45a049;
}

.file-info {
    margin-top: 20px;
    font-size: 18px;
}

.progress-container {
    margin-top: 20px;
    background-color: #e0e0e0;
    border-radius: 25px;
    width: 100%;
    height: 15px;
    box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.15);
    position: relative;
}

.progress-bar {
    height: 100%;
    background-color: #76c7c0; /* Light teal color */
    border-radius: 25px 0 0 25px; /* Rounded left side */
    transition: width 0.3s ease-in-out;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
}

.upload-complete {
    margin-top: 20px;
    color: green;
    font-weight: bold;
}

.retrying {
    margin-top: 20px;
    color: orange;
    font-weight: bold;
}
</style>
