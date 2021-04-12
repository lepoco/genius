// This Source Code Form is subject to the terms of the GNU General Public License, Version 3.
// If a copy of the GPL was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.txt.
// Copyright (C) 2021 Leszek Pomianowski
// All Rights Reserved.

namespace Genius.Code.Engine.Predictor
{
    /// <summary>
    /// The state of the answer to the question.
    /// </summary>
    public enum Response
    {
        /// <summary>
        /// The question was not answered.
        /// </summary>
        Unknown = 0,
        
        /// <summary>
        /// It is not known whether the answer meets the condition or not
        /// </summary>
        Indeterminate,

        /// <summary>
        /// The answer certainly meets the condition for the answer.
        /// </summary>
        Confirmed,

        /// <summary>
        /// The answer certainly does not meet the condition for an answer.
        /// </summary>
        Negated,

        /// <summary>
        /// The answer probably meets the condition for answering. The result is unknown, fuzzy sets should be used.
        /// </summary>
        Probable,

        /// <summary>
        /// The answer probably does not meet the condition for an answer. The result is unknown, fuzzy sets should be used.
        /// </summary>
        Improbable
    }
}
