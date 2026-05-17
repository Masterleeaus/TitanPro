<x-filament-panels::page>
    <div x-data="{ tab: 'url' }" class="space-y-6">
        <div class="space-y-2">
            <label class="text-sm font-medium">Chatbot</label>
            <select wire:model="chatbotId" class="fi-input block w-full rounded-lg border-gray-300">
                @foreach($this->chatbotOptions() as $id => $label)
                    <option value="{{ $id }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex flex-wrap gap-2">
            <button type="button" class="fi-btn fi-btn-color-gray fi-btn-size-sm" :class="{ 'fi-btn-color-primary': tab === 'url' }" @click="tab = 'url'">URL</button>
            <button type="button" class="fi-btn fi-btn-color-gray fi-btn-size-sm" :class="{ 'fi-btn-color-primary': tab === 'file' }" @click="tab = 'file'">File</button>
            <button type="button" class="fi-btn fi-btn-color-gray fi-btn-size-sm" :class="{ 'fi-btn-color-primary': tab === 'text' }" @click="tab = 'text'">Text</button>
            <button type="button" class="fi-btn fi-btn-color-gray fi-btn-size-sm" :class="{ 'fi-btn-color-primary': tab === 'qa' }" @click="tab = 'qa'">Q&amp;A</button>
        </div>

        <form x-show="tab === 'url'" wire:submit.prevent="trainFromUrl" class="space-y-3">
            <label class="text-sm font-medium">Website URL</label>
            <input type="url" wire:model.defer="url" class="fi-input block w-full rounded-lg border-gray-300" placeholder="https://example.com" />
            <button type="submit" class="fi-btn fi-btn-color-primary">Crawl + Embed</button>
        </form>

        <form x-show="tab === 'file'" wire:submit.prevent="trainFromFile" class="space-y-3">
            <label class="text-sm font-medium">Upload file (PDF, DOCX, XLSX)</label>
            <input type="file" wire:model="fileUpload" class="fi-input block w-full rounded-lg border-gray-300" />
            <button type="submit" class="fi-btn fi-btn-color-primary">Parse + Embed</button>
        </form>

        <form x-show="tab === 'text'" wire:submit.prevent="trainFromText" class="space-y-3">
            <label class="text-sm font-medium">Text content</label>
            <textarea wire:model.defer="textInput" rows="8" class="fi-input block w-full rounded-lg border-gray-300" placeholder="Paste training text here..."></textarea>
            <button type="submit" class="fi-btn fi-btn-color-primary">Embed Text</button>
        </form>

        <form x-show="tab === 'qa'" wire:submit.prevent="trainFromQa" class="space-y-3">
            <label class="text-sm font-medium">Question</label>
            <input type="text" wire:model.defer="question" class="fi-input block w-full rounded-lg border-gray-300" placeholder="What is your refund policy?" />
            <label class="text-sm font-medium">Answer</label>
            <textarea wire:model.defer="answer" rows="5" class="fi-input block w-full rounded-lg border-gray-300" placeholder="Describe the answer here..."></textarea>
            <button type="submit" class="fi-btn fi-btn-color-primary">Embed Q&amp;A</button>
        </form>
    </div>
</x-filament-panels::page>
