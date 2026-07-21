

<div class="fixed inset-y-0 left-0 z-40 w-auto overflow-y-auto border-r bg-white dark:bg-dark-bg border-zinc-900/10 dark:border-white/10 lg:block xl:w-50 transition-all">
  @livewire('header')
  <aside class="lg:block px-3 py-4 lg:static fixed w-42 z-[9999999] bg-white dark:bg-dark-bg lg:shadow-none shadow-sm lg:dark:shadow-none dark:shadow-white/30 dark:shadow-glow transition-all duration-300 lg:rounded-none rounded-tr-lg rounded-br-lg lg:mt-0 mt-15 -left-[250px]">
    <ul class="list-none space-y-1">
      @include('livewire.common.menu')
    </ul>
  </aside>
</div>