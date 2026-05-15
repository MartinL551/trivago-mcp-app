import { index } from "@/actions/App/Http/Controllers/SearchController";
import { onMounted, onUnmounted, ref } from "vue";


export function useSearchPolling(
    searchRequestId: number,
    currentAccomState: any[],
) {
    const status = ref('pending');
    const accommodations = ref([...currentAccomState]);

    let intervalId: number | undefined;

    const poll = async () => {
        const requestedIds: Array<number> = [];

        accommodations.value.map(accom => {
            if(!accom.scores) {
                requestedIds.push(accom.id);
            }
        });

        const response = await fetch(`/search/${searchRequestId}/poll`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                known_ids: requestedIds,
            }),
        })

        if (!response.ok) {
            throw new Error('Polling failed')
        }

        const data = await response.json()

        status.value = data.status

        if(data.accommodations?.length > 0) {
            data.accommodations.forEach(accom => {
                const id = accom.id;
                const currentAccoms = accommodations.value;
                const indexToUpdate = currentAccoms.findIndex(currAccom => currAccom.id = id);

                if(indexToUpdate != -1) {
                    currentAccoms[indexToUpdate] = accom;
                } else {
                    currentAccoms.push(accom);
                }

            });
        }
    }
}