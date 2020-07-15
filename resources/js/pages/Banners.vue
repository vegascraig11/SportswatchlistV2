<template>
	<div class="container mx-auto py-6">
        <h1 class="text-2xl">Banners Management</h1>

        <div class="mt-4">
            <h2>
                <span class="text-xl">Banners</span> 
                (<span @click="toggleBannersTable" class="underline font-semibold text-gray-500 hover:text-gray-700 cursor-pointer">{{ banners.length }} banners</span>)
            </h2>

            <table v-if="showTable" class="mt-4 w-full">
                <thead>
                    <tr class="text-left">
                        <th class="border-t border-b px-4 py-2">Preview</th>
                        <th class="border-t border-b px-4 py-2">URL</th>
                        <!-- <th class="border-t border-b px-4 py-2">Status</th> -->
                        <th class="border-t border-b px-4 py-2">...</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="banner in banners" class="group hover:bg-gray-200">
                        <td class="border-t border-b px-4 py-2">
                            <img class="w-32" :src="`/${banner.path}`" :alt="banner.filename">
                        </td>
                        <td class="border-t border-b px-4 py-2">{{ banner.url }}</td>
                        <!-- <td class="border-t border-b px-4 py-2">Adam</td> -->
                        <td class="border-t border-b px-4 py-2">
                            <button @click="deleteBanner(banner.id)" type="button" class="invisible group-hover:visible px-4 py-2 border rounded border-red-500 text-red-500 hover:bg-red-200 hover:text-red-800">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </button></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <form class="mt-8" @submit.prevent="upload" method="post">
            <h2 class="text-xl">Upload a Banner</h2>
            <div class="mt-4">
                <span class="uppercase">Banner to upload</span>
                <div>
                    <input ref="uploadInput" type="file" class="hidden" @change="update" required>
                    <button type="button" @click="openUploader" class="px-4 py-2 w-full max-w-sm rounded bg-mantis-500 hover:bg-mantis-600 text-white">
                        <span v-if="banner" class="">{{ banner.name }}</span>
                        <span v-else>Select an image</span>
                    </button>
                </div>
            </div>
    
            <div class="mt-4" v-if="preview">
                <h3 class="uppercase">Banner Preview</h3>
                <img v-if="preview" :src="preview" alt="Banner" class="max-w-sm">
            </div>
    
            <div class="mt-4 max-w-sm">
                <span class="block uppercase">Banner URL</span>
                <span v-if="urlError" class="text-red-500 pb-1"><em>{{ urlError }}</em></span>
                <input ref="bannerUrlInput" v-model="url" required type="text" class="w-full px-4 py-2 border border-gray-300 rounded placeholder-gray-600" :class="urlError ? 'border-red-500' : ''" @blur="validateUrl" placeholder="Enter the URL asscoiated with the banner.">
            </div>
    
            <div class="mt-4">
                <button class="bg-mantis-500 hover:bg-mantis-600 text-white rounded px-4 py-2">Upload</button>
            </div>
        </form>
    </div>
</template>

<script>
import isValidDomain from 'is-valid-domain'

export default {
    data() {
        return {
            preview: '',
            banner: null,
            url: '',
            banners: [],
            showTable: false,
            urlError: ''
        }
    },
    created() {
        this.getBanners()
    },
	methods: {
        getBanners() {
            this.$http.get('/api/banners')
                .then(response => this.banners = response.data)
                .catch(err => console.log(err))
        },
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
            if (! this.banner) return false
            if (! this.url) return false

            return true
        },
        validateUrl(e) {
            let url = e.target.value
            let hasHttp = url.trim().substr(0, 7) === 'http://'

            if (hasHttp) {
                url = url.substr(7)
            }

            console.log(url)

            if (! isValidDomain(url)) {
                console.log('not a valid domain name')
                this.urlError = "Invalid domain name provided."
                return false
            }

            this.urlError = ''

            this.url = 'http://' + url

            return true
        },
        toggleBannersTable() {
            this.showTable = !this.showTable
        },
        deleteBanner(id) {
            this.$http.delete(`/api/banners/${id}`)
                .then(() => this.getBanners())
                .catch(err => console.log(err))
        }
	}
}
</script>