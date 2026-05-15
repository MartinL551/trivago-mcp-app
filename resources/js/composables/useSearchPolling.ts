import { onMounted, onUnmounted, ref } from "vue";
import type { SearchResult, AccommidationResults } from '@/types';

export function useSearchPolling(
    currentSearchRequest: SearchResult,
    currentAccomState: AccommidationResults,
) {
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
            requestedIds = accommodations.value.map((accom) => {
                return !accom.scores ? accom.id : null;
            });
        }

        console.log(knownIds);
        console.log(requestedIds);
        
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

        const data = await response.json()

        searchRequest.value.status = data.status

        if(data.accommodations?.length > 0) {
            const currentAccoms = accommodations.value;

            data.accommodations.forEach(accom => {
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