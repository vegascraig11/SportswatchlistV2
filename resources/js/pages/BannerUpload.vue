<template>
	<div class="container mx-auto py-6">
        <form @submit.prevent="upload" method="post">
        <h1 class="text-xl">Banner Upload</h1>
    
            <div class="mt-4">
                <span class="uppercase">Choose a banner to upload</span>
                <div>
                    <input ref="uploadInput" type="file" class="hidden" @change="update" required>
                    <button type="button" @click="openUploader" class="px-4 py-2 w-64 rounded bg-mantis-500 hover:bg-mantis-600 text-white">Select an image</button>
                </div>
            </div>
    
            <div class="mt-4">
                <h3 class="uppercase">Banner Preview</h3>
                <img v-if="preview" :src="preview" alt="Banner" class="max-w-sm">
            </div>
    
            <div class="mt-4 max-w-sm">
                <span class="block uppercase">Banner URL</span>
                <input ref="bannerUrlInput" v-model="url" required type="text" class="w-full px-4 py-2 border border-gray-300 rounded placeholder-gray-600" placeholder="Enter the URL asscoiated with the banner.">
            </div>
    
            <div class="mt-4">
                <button class="bg-mantis-500 hover:bg-mantis-600 text-white rounded px-4 py-2">Upload</button>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            preview: '',
            banner: {},
            url: ''
        }
    },
	methods: {
		openUploader() {
			this.$refs.uploadInput.click();
		},
        upload() {
            if (! this.validateFields()) return false

            const formData = new FormData()

            formData.append('banner', this.banner)
            formData.append('url', this.url)

            const config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            }

            this.$http.post('/api/banners', formData, config)
                .then(response => {
                    flash({
                        type: 'success',
                        body: 'Banner was uploaded!'
                    })
                    this.$router.push('/admin')
                })
                .catch(err => console.log(err))
        },
        update(event) {
            const file = event.target.files[0]

            if (! file) return false

            this.banner = file

            const reader = new FileReader()

            reader.onload = e => {
                this.preview = e.target.result
            }

            reader.readAsDataURL(file)
        },
        validateFields() {
            console.log('banner', this.banner)
            console.log('url', this.url)

            return true
        }
	}
}
</script>