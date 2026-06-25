import { onMounted, onUnmounted, ref } from "vue";
import type { AccommodationResult, AccommidationResults, SearchResult } from '@/types';

type PollResponse = {
    status: SearchResult['status'];
    prompt: SearchResult['prompt'];
    accommodations: AccommodationResult[] | null;
};

export function useSearchPolling(
    currentSearchRequest: SearchResult,
    currentAccomState: AccommidationResults,
) {
    console.log('test', currentAccomState);
    const searchRequest = ref(currentSearchRequest);
    const accommodations = currentAccomState ? ref([...currentAccomState]) : ref([]);
    const SCORING_STATUSES = ['scoring'];
    const STOP_STATUSES = ['failed', 'complete'];
    const INTERVAL: number = 2000;

    let intervalId: number | undefined;
    let isPolling: boolean = false;
    let retried: number = 0;

    const poll = async () => {
        const knownIds: Array<number> = accommodations.value.map(accom => accom.id);
        let requestedIds: Array<number> = [];

        if(isPolling) {
            return;
        }

        if(SCORING_STATUSES.includes(searchRequest.value.status)){
            console.log('test2', accommodations.value);
            requestedIds = accommodations.value
                .filter((accom) => accom.scores === undefined)
                .map((accom) => accom.id);
        }

        console.log('Known', knownIds);
        console.log('requested', requestedIds);
        
        isPolling = true;

        const response = await fetch(`/results/${searchRequest.value.id}/poll`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                known_ids: knownIds,
                requested_ids: requestedIds, 
            }),
        })

        if (!response.ok) {
            retried++;

            if(retried > 5){
                console.error('Failed to get response to poll');
                stopPolling();
            }

            return;
        } else{
            isPolling = false;
        }

        const data = await response.json() as PollResponse;

        searchRequest.value.status = data.status

        if(data.accommodations && data.accommodations.length > 0) {
            const currentAccoms = accommodations.value;

            data.accommodations.forEach((accom) => {
                const indexToUpdate = currentAccoms.findIndex(currAccom => currAccom.id === accom.id);

                if(indexToUpdate != -1) {
                    currentAccoms[indexToUpdate] = accom;
                } else {
                    currentAccoms.push(accom);
                }
            });
        }

        if (STOP_STATUSES.includes(searchRequest.value.status)) {
            stopPolling();   
        }
    
    }

    const startPolling = () => {
        poll();

        intervalId = window.setInterval(() => {
            poll()
        }, INTERVAL);
    }

    const stopPolling = () => {
        if (intervalId) {
            clearInterval(intervalId);
        }
    }

    onMounted(startPolling);
    onUnmounted(stopPolling);

    return {
        searchRequest,
        accommodations,
    }
}
