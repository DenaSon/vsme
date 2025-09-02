<div>

    <div class="mt-6 flex items-start justify-between gap-3">
        <div>

            <p class="text-sm text-base-content/70">Question 20/80</p>
            <h1 class="text-2xl md:text-3xl font-extrabold leading-tight mt-1">
                This is Q2
            </h1>
        </div>

        <button class="btn btn-ghost btn-circle" aria-label="Read out loud">
            <x-heroicon-o-speaker-wave class="w-5 h-5"/>
        </button>
    </div>


    <div class="mt-6 space-y-3" role="radiogroup" aria-label="option">
        <label class="block">
            <input type="radio" name="option"
                   class="peer sr-only"
                   value="basic" />
            <div
                class="card bg-base-100 border border-base-300 rounded-2xl transition
               peer-checked:border-primary peer-checked:bg-primary/5
               peer-focus-visible:ring peer-focus-visible:ring-primary/30
               peer-focus-visible:ring-offset-2 peer-focus-visible:ring-offset-base-100">
                <div class="card-body px-5 py-4">
                <span class="text-base md:text-lg font-semibold peer-checked:text-primary">
                    Just the basic module Q2
                </span>
                </div>
            </div>
        </label>

    </div>






    <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-4">


        <button type="button"
                class="btn btn-outline w-full sm:w-auto min-w-[7.5rem] order-2 sm:order-1"
                wire:click="back">
            <x-heroicon-o-arrow-left class="w-5 h-5 me-2"/> Back
        </button>


        <div class="flex flex-col sm:flex-row items-stretch gap-2 w-full sm:w-auto order-1 sm:order-2">


            <button type="button"
                    class="btn btn-primary w-full sm:w-auto min-w-[7.5rem] order-1 sm:order-2"
                    wire:click="next">
                Next <x-heroicon-o-arrow-right class="w-5 h-5 ms-2"/>
            </button>


            <details class="dropdown dropdown-end w-full sm:w-auto order-3 sm:order-1">
                <summary class="btn btn-outline w-full sm:w-auto min-w-[7rem] justify-between sm:justify-center">
                    Skip <x-heroicon-o-chevron-down class="w-4 h-4 ms-1"/>
                </summary>
                <ul class="dropdown-content menu p-2 shadow-md bg-base-100 rounded-box w-56 max-w-[90vw] right-0">
                    <li><button type="button" wire:click="skip('na')">N/A</button></li>
                    <li><button type="button" wire:click="skip('classified')">Classified Information</button></li>
                </ul>
            </details>
        </div>
    </div>




</div>

