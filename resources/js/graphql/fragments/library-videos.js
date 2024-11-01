/**
 * External dependencies.
 */
import gql from 'graphql-tag';

const LibraryVideoFragment = gql`
    fragment LibraryVideoFragment on LibraryVideo {
        id
        title
        watchable {
            id
            thumbnailUrl
            videoUrl
            downloadUrl
            isVideoPortrait
            ... on Video {
                gifUrl
            }
        }
    }
`;

export default {
    LibraryVideoFragment,
};
