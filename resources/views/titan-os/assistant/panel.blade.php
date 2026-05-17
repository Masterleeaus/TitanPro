{{--
  Titan Zero Assistant Panel

  Contains the chat transcript, input form and suggestions.  This panel is
  embedded within the assistant dock.  Messages are appended via
  titan-zero-assistant.js.
--}}
<div id="titan-zero-chat-panel" class="flex flex-col h-full relative">
    <!-- Thread header: shows current thread title, app context and actions -->
    <div class="titan-zero-thread-header flex items-center justify-between p-2 border-b bg-white dark:bg-gray-900" data-thread-header>
        <div class="flex items-center space-x-2">
            <span data-current-thread-title class="font-medium truncate">New Thread</span>
            <span data-current-thread-app class="text-xs text-gray-500 truncate"></span>
        </div>
        <div class="flex items-center space-x-1 text-xs">
            <!-- Dropdown toggle to select a different thread -->
            <button type="button" data-thread-dropdown-toggle class="px-2 py-1 rounded bg-gray-200 dark:bg-gray-700">Threads ▼</button>
            <!-- Create a new thread -->
            <button type="button" data-new-thread class="px-2 py-1 rounded bg-gray-200 dark:bg-gray-700">New</button>
        </div>
        <!-- Dropdown panel for thread list.  Hidden by default and toggled via JS. -->
        <div class="titan-zero-thread-dropdown-panel absolute right-0 mt-1 w-48 bg-white dark:bg-gray-900 border rounded shadow-lg z-10 hidden" data-thread-dropdown-panel>
            <ul class="max-h-60 overflow-y-auto text-sm" data-thread-list>
                <!-- Dynamically populated via JS -->
            </ul>
        </div>
    </div>
    <div data-message-list class="flex-1 overflow-y-auto space-y-1 p-2" data-message-container>
        <!-- Initial assistant greeting -->
        <div class="titan-zero-message titan-zero-message-assistant p-2 my-1">
            Titan Zero is ready. Ask me to navigate, explain this screen, or open an app.
        </div>
    </div>
    <div class="p-2 border-t bg-white dark:bg-gray-900">
        <div class="flex flex-wrap gap-1 mb-2">
            <button type="button" data-suggestion="Open app" class="text-xs px-2 py-1 bg-gray-200 dark:bg-gray-700 rounded">Open app</button>
            <button type="button" data-suggestion="Search workspace" class="text-xs px-2 py-1 bg-gray-200 dark:bg-gray-700 rounded">Search workspace</button>
            <button type="button" data-suggestion="Explain this screen" class="text-xs px-2 py-1 bg-gray-200 dark:bg-gray-700 rounded">Explain this screen</button>
            <button type="button" data-suggestion="Show recent activity" class="text-xs px-2 py-1 bg-gray-200 dark:bg-gray-700 rounded">Show recent activity</button>
            <button type="button" data-suggestion="Help me navigate" class="text-xs px-2 py-1 bg-gray-200 dark:bg-gray-700 rounded">Help me navigate</button>
        </div>
        <form class="flex items-center space-x-2" data-chat-form>
            <textarea class="flex-1 resize-none border rounded p-2 dark:bg-gray-800 dark:border-gray-700" rows="1" placeholder="Type a message…"></textarea>
            <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded">Send</button>
        </form>
    </div>
</div>