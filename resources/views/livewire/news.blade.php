<div x-data="{
    showModal: false,
    currentNews: null,
    newsItems: {{ json_encode($news->toArray()) }},

    openModal(news) {
        this.currentNews = news;
        this.showModal = true;
        document.body.classList.add('overflow-hidden');
    },

    closeModal() {
        this.showModal = false;
        document.body.classList.remove('overflow-hidden');
    },

    nextNews() {
        const currentIndex = this.newsItems.findIndex(item => item.id === this.currentNews.id);
        if (currentIndex < this.newsItems.length - 1) {
            this.currentNews = this.newsItems[currentIndex + 1];
        }
    },

    prevNews() {
        const currentIndex = this.newsItems.findIndex(item => item.id === this.currentNews.id);
        if (currentIndex > 0) {
            this.currentNews = this.newsItems[currentIndex - 1];
        }
    }
}">
    <!-- Card Blog -->
    <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
        <!-- Title -->
        <div class="max-w-2xl mx-auto text-center mb-10 lg:mb-14">
            <h2 class="text-6xl font-bold md:text-4xl md:leading-tight nunito-sans-bold">{{ __('News') }}</h2>
            <p class="mt-1 text-gray-600 poppins-medium">{{ __('Stay updated with our latest announcements and events') }}</p>
        </div>
        <!-- End Title -->

        <!-- Grid -->
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($news as $newsItem)
                <!-- Card -->
                <div
                    class="group flex flex-col h-full focus:outline-hidden bg-white rounded-xl shadow-sm hover:shadow-md transition duration-300 cursor-pointer"
                    @click="openModal({{ $newsItem }})"
                >
                    <div class="relative pt-[50%] sm:pt-[70%] rounded-t-xl overflow-hidden">
                        @if($newsItem->image)
                            <img class="size-full absolute top-0 start-0 object-cover group-hover:scale-105 group-focus:scale-105 transition-transform duration-500 ease-in-out rounded-t-xl"
                                src="{{ Storage::url($newsItem->image) }}"
                                alt="{{ $newsItem->title }}">
                        @else
                            <div class="size-full absolute top-0 start-0 bg-gray-200 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                        @endif

                        @if($loop->first)
                            <span class="absolute top-0 end-0 rounded-se-xl rounded-es-xl text-xs font-medium bg-blue-600 text-white py-1.5 px-3">
                                {{ __('Featured') }}
                            </span>
                        @endif

                        <span class="absolute bottom-0 start-0 rounded-ee-xl rounded-tr-xl text-xs font-medium bg-gray-800 text-white py-1.5 px-3">
                            {{ $newsItem->created_at->format('d M Y') }}
                        </span>
                    </div>

                    <div class="p-5 flex flex-col flex-grow">
                        <h3 class="text-xl font-semibold text-gray-800 group-hover:text-blue-600 line-clamp-2">
                            {{ $newsItem->title }}
                        </h3>
                        <p class="mt-3 text-gray-600 line-clamp-3 flex-grow">
                            {{ Str::limit(strip_tags($newsItem->content_en ?? $newsItem->content), 150) }}
                        </p>
                        <p class="mt-5 inline-flex items-center gap-x-1 text-sm text-blue-600 decoration-2 group-hover:underline group-focus:underline font-medium">
                            {{ __('Read more') }}
                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                        </p>
                    </div>
                </div>
                <!-- End Card -->
            @empty
                <!-- Empty State -->
                <div class="col-span-full py-12">
                    <div class="flex flex-col items-center justify-center text-center">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-medium text-gray-900 mb-2">{{ __('No news available') }}</h3>
                        <p class="text-gray-500 max-w-md">{{ __('There are no news articles available at this time. Please check back later for updates.') }}</p>
                    </div>
                </div>
                <!-- End Empty State -->
            @endforelse
        </div>
        <!-- End Grid -->
    </div>
    <!-- End Card Blog -->

    <!-- Modal para mostrar detalles completos de la noticia -->
    <div
        x-show="showModal"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div
                class="fixed inset-0 transition-opacity bg-white bg-opacity-75"
                @click="closeModal()"
                aria-hidden="true"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
            ></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block w-full max-w-4xl overflow-hidden text-left align-middle transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle"
                @click.away="closeModal()"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            >
                <div class="relative">
                    <!-- Botón de cerrar -->
                    <button
                        @click="closeModal()"
                        class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 focus:outline-none z-10 cursor-pointer"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>

                    <!-- Imagen principal (si existe) -->
                    <div class="relative" x-show="currentNews && currentNews.image">
                        <img
                            :src="currentNews ? '/'+currentNews.image : ''"
                            :alt="currentNews ? currentNews.title : ''"
                            class="w-full h-64 object-cover"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    </div>

                    <!-- Contenido del artículo -->
                    <div class="p-6 pt-4 max-h-[80vh] overflow-y-auto">
                        <div class="flex items-center text-sm text-gray-500 mb-2">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                                <span x-text="currentNews ? new Date(currentNews.created_at).toLocaleDateString() : ''"></span>
                            </span>
                            <span class="mx-2">•</span>
                            <span x-text="currentNews ? currentNews.category || 'News' : ''"></span>
                        </div>

                        <h2 class="text-2xl font-bold text-gray-900 mb-4" x-text="currentNews ? currentNews.title : ''"></h2>

                        <div class="prose prose-lg max-w-none" x-html="currentNews ? (currentNews.content_en || currentNews.content) : ''"></div>

                        <!-- Pie del modal con botones de navegación y compartir -->
                        <div class="mt-8 pt-4 border-t border-gray-200">
                            <div class="flex justify-between items-center">
                                <!-- Navegación entre artículos -->
                                <div class="flex space-x-3">
                                    <button
                                        @click="prevNews()"
                                        :disabled="newsItems.findIndex(item => item.id === currentNews?.id) <= 0"
                                        :class="newsItems.findIndex(item => item.id === currentNews?.id) <= 0 ? 'opacity-50 cursor-not-allowed' : ''"
                                        class="p-2 rounded-full hover:bg-gray-100 transition-colors"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                    </button>
                                    <button
                                        @click="nextNews()"
                                        :disabled="newsItems.findIndex(item => item.id === currentNews?.id) >= newsItems.length - 1"
                                        :class="newsItems.findIndex(item => item.id === currentNews?.id) >= newsItems.length - 1 ? 'opacity-50 cursor-not-allowed' : ''"
                                        class="p-2 rounded-full hover:bg-gray-100 transition-colors"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Botones para compartir -->
                                <div class="flex space-x-2">
                                    <button
                                        @click="window.open('https://twitter.com/intent/tweet?text=' + encodeURIComponent(currentNews.title) + '&url=' + encodeURIComponent(window.location.origin + '/news/' + currentNews.id), '_blank')"
                                        class="p-2 text-gray-600 hover:text-blue-500 hover:bg-gray-100 rounded-full transition-colors"
                                    >
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                                        </svg>
                                    </button>
                                    <button
                                        @click="window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(window.location.origin + '/news/' + currentNews.id), '_blank')"
                                        class="p-2 text-gray-600 hover:text-blue-700 hover:bg-gray-100 rounded-full transition-colors"
                                    >
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <button
                                        @click="const url = window.location.origin + '/news/' + currentNews.id; navigator.clipboard.writeText(url); alert('Link copied to clipboard!')"
                                        class="p-2 text-gray-600 hover:text-green-500 hover:bg-gray-100 rounded-full transition-colors"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</div>
