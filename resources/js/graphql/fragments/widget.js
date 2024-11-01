/**
 * External dependencies.
 */
import gql from 'graphql-tag';

/**
 * Internal dependencies.
 */
import LibraryVideosFragments from './library-videos';

const BubbleWidgetDataFragment = gql`
    fragment BubbleWidgetDataFragment on BubbleWidget {
        id
        title
        showBackdrop
        font
        buttonColor
        backgroundColor
        borderColor
        fitVideo
        size
        previewTextFontSize
        availabilityUrls
        useAsDefaultForAllPages
    }
`;

const SignatureWidgetDataFragment = gql`
    fragment SignatureWidgetDataFragment on SignatureWidget {
        id
        borderColor
        websiteUrl
        phoneNumber
        imageUrl
    }
`;

const PageDataWidgetFragment = gql`
    fragment PageDataWidgetFragment on PageWidget {
        id
        font
        style
        autoplay
        fitVideo
        buttonColor
        backgroundColor
    }
`;

const BusinessCardFragment = gql`
    fragment BusinessCardFragment on BusinessCardWidget {
        id
        email
        fitVideo
        autoplay
        backgroundColor
        buttonColor
        websiteUrl
        phoneNumber
    }
`;

const FullWidgetDataFragment = gql`
    fragment FullWidgetDataFragment on Widget {
        id
        uniqueId
        name
        brandingOptions
        publicUrl
        user {
            id
            image
        }
        widgetable {
            ... on BubbleWidget {
                ...BubbleWidgetDataFragment
            }

            ... on SignatureWidget {
                ...SignatureWidgetDataFragment
            }

            ... on PageWidget {
                ...PageDataWidgetFragment
            }

            ... on BusinessCardWidget {
                ...BusinessCardFragment
            }
        }
        libraryVideo {
            ...LibraryVideoFragment
        }
    }
    ${BusinessCardFragment}
    ${PageDataWidgetFragment}
    ${BubbleWidgetDataFragment}
    ${SignatureWidgetDataFragment}
    ${LibraryVideosFragments.LibraryVideoFragment}
`;

export default {
    BusinessCardFragment,
    PageDataWidgetFragment,
    BubbleWidgetDataFragment,
    SignatureWidgetDataFragment,
    FullWidgetDataFragment,
};
